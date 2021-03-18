<?php

namespace App\Console\Commands;

use App\Mail\LegoAdded;
use App\Mail\LegoDeleted;
use App\Mail\LegoUpdated;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

use App\Models\LegoItem;
use Goutte\Client;

class ScrapeLego extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:lego {--market=uk} {--silent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Srapes the Lego "Retiring soon" page and updates database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $market = $this->option('market');

        $url = $market == 'us' ? 'https://www.lego.com/en-us/categories/retiring-soon' : 'https://www.lego.com/en-gb/categories/retiring-soon';
        $client = new Client();

        $this->info("Getting data from $url");
        // echo "Getting data from $url \n";

        $crawler = $client->request('GET', $url);

        $this->info("Crawling results...");
        // echo "Crawling results...\n";

        // Crawl the first page
        $result = $this->getResultsFromCrawler($crawler, $market);
        // Get total number of pages
        $pages = $this->getTotalPages($crawler);
        // Crawl other pages starting from page 2
        for ($i = 2; $i <= $pages; $i++) {
            $newUrl = "$url?page=$i";
            $crawler = $client->request('GET', $newUrl);
            $newResult = $this->getResultsFromCrawler($crawler, $market);
            $result = array_merge($result, $newResult);
        }

        // Reset database
        // LegoItem::truncate();
        // LegoItem::insert($result);

        $this->info("Merging result set with database...");
        // echo "Merging result set with database... \n";

        $this->mergeChangesWithDb($result, $market);

        $this->info("Lego data scraped for $market market");
        // echo "Lego data scraped for $market market \n";
        return 0;
    }

    private function mergeChangesWithDb($scrapedResults, $market)
    {
        $silent = $this->option('silent');

        $recipientEmail = env('MAIL_RECIPEINT', 'nishatsayyed26@gmail.com');

        // NOTE: $scrapedResults always takes precedence over db records.
        $legos = LegoItem::where(['marketplace' => $market == 'us' ? 'US' : 'UK'])->get();
        // to sync db with scraped result, find out data that is present in db but not in scraped result
        foreach ($legos as $index => $lego) {
            $legoSerialized = $lego->makeHidden(['id', 'created_at', 'updated_at'])
                ->toArray();
            $filteredArray = array_filter($scrapedResults, function ($result) use ($legoSerialized) {
                return $legoSerialized['number'] == $result['number'];
            });
            if (count($filteredArray) == 0) {
                // if record not found in scrapedResults - delete record from db, email update (use delete email template)
                $lego->delete();
                // alert only if element is in watchlist
                if (!$silent && $lego->watch) {
                    Mail::to($recipientEmail)->send(new LegoDeleted($lego->toArray()));
                }
                // update in-memory db collection
                unset($legos[$index]);
            }
        }

        // iterate through each scraped result
        foreach ($scrapedResults as $index => $result) {
            // for each scraped result try to get record from db where item number matches the number in scraped result
            $filteredArray = array_filter($legos->toArray(), function ($lego) use ($result) {
                return $lego['number'] == $result['number'];
            });

            if (count($filteredArray) > 0) {
                // if element found - check if there are any changes between scraped result and the db record
                foreach ($filteredArray as $lego) {
                    // clean data
                    // skip date_spotted attribute
                    unset($lego['date_spotted']);
                    unset($result['date_spotted']);
                    $lego['price'] = (float) $lego['price'];
                    $lego['sale_price'] = (float) $lego['sale_price'];
                    $lego['discount_amount'] = (float) $lego['discount_amount'];

                    $diff = array_diff($lego, $result);
                    if (count($diff) > 0) {
                        // if changes found - update db record with scraped result and email update (use update email template)
                        LegoItem::where(['url' => $result['url']])->update($result);
                        // alert only if element is in watchlist
                        if (!$silent && $lego['watch']) {
                            Mail::to($recipientEmail)->send(new LegoUpdated($result));
                        }
                    }
                }
            } else if (count($filteredArray) == 0) {
                //  if element not found - create a new record in db with this scraped result and email update (use create email template)
                LegoItem::insert($result);
                if (!$silent) {
                    Mail::to($recipientEmail)->send(new LegoAdded($result));
                }
            }
        }
    }

    private function getTotalPages($crawler)
    {
        $pagesSummary = $crawler->filter('div[data-test="listing-summary"]')->text();
        $itemsPerPage = 17;
        $totalItems = intval(explode(' ', $pagesSummary)[5]);
        $pageNumbers = (int)($totalItems / $itemsPerPage);
        if ($totalItems % $itemsPerPage > 0) {
            $pageNumbers = $pageNumbers + 1;
        }
        return $pageNumbers;
    }

    private function getResultsFromCrawler($crawler, $market)
    {
        $priceDelimiter = $market == 'us' ? '$' : '£';
        $result = $crawler->filter('li[data-test="product-item"] div[data-test="product-leaf"]')->each(function ($node) use ($market, $priceDelimiter) {
            $marketplace = $market == 'us' ? 'US' : 'UK';
            $url = $node->filter('[data-test="product-leaf-title-link"]')->first()->attr('href');
            $name = substr($node->filter('[data-test="product-leaf-title"]')->text(), 0, 40);
            $itemNumber = intval(substr($url, strrpos($url, '-') + 1));
            $priceText = $node->filter('[data-test="product-price"]')->text();
            $price = floatval(str_replace($priceDelimiter, '', substr($priceText, strrpos($priceText, $priceDelimiter))));
            $salePrice = $price;
            if ($node->filter('[data-test="product-price-sale"]')->count() > 0) {
                $salePriceText = $node->filter('[data-test="product-price-sale"]')->text();
                $salePrice = floatval(str_replace($priceDelimiter, '', substr($salePriceText, strrpos($salePriceText, $priceDelimiter))));
            }
            $discountAmount = 0.00;
            if ($salePrice != null && $price > $salePrice) {
                $discountAmount = (float)(number_format((float) $price - $salePrice, 2, '.', ''));
            }
            $discountPercentage = 0;
            if ($discountAmount != 0) {
                $discountPercentage = round(($discountAmount * 100) / $price);
            }
            $status = 'Available';
            if ($node->filter('span[type="outOfStock"]')->count() > 0) {
                $status = 'Out of stock';
            }
            return [
                'marketplace' => $marketplace,
                'name' => $name,
                'number' => $itemNumber,
                'url' => 'www.lego.com' . $url,
                'price' => $price,
                'sale_price' => $salePrice,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'date_spotted' => now()->toDateString(),
                'stock_status' => $status
            ];
        });
        return $result;
    }
}
