<?php

namespace App\Http\Controllers;

use App\Models\LegoItem;
use Illuminate\Http\Request;
use Goutte\Client;

class LegoItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $market = request()->query('market');
        $priceDelimiter = $market == 'us' ? '$' : '£';
        $url = $market == 'us' ? 'https://www.lego.com/en-us/categories/retiring-soon' : 'https://www.lego.com/en-gb/categories/retiring-soon';
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $result = $crawler->filter('li[data-test="product-item"] div[data-test="product-leaf"]')->each(function ($node) use ($market, $priceDelimiter) {
            $marketplace = $market == 'us' ? 'US' : 'UK';
            $url = $node->filter('[data-test="product-leaf-title-link"]')->first()->attr('href');
            $name = $node->filter('[data-test="product-leaf-title"]')->text();
            $itemNumber = substr($url, strrpos($url, '-') + 1);
            $priceText = $node->filter('[data-test="product-price"]')->text();
            $price = floatval(str_replace($priceDelimiter, '', substr($priceText, strrpos($priceText, $priceDelimiter))));
            $salePrice = null;
            if ($node->filter('[data-test="product-price-sale"]')->count() > 0) {
                $salePriceText = $node->filter('[data-test="product-price-sale"]')->text();
                $salePrice = floatval(str_replace($priceDelimiter, '', substr($salePriceText, strrpos($salePriceText, $priceDelimiter))));
            }
            $discountAmount = 0;
            if ($salePrice != null && $price > $salePrice) {
                $discountAmount = floatval(number_format((float) $price - $salePrice, 2, '.', ''));
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
                'url' => 'www.lego.com' . $url,
                'item_number' => $itemNumber,
                'price' => $price,
                'sale_price' => $salePrice,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'date_spotted' => null,
                'stock_status' => $status
            ];
        });
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LegoItem  $legoItem
     * @return \Illuminate\Http\Response
     */
    public function show(LegoItem $legoItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LegoItem  $legoItem
     * @return \Illuminate\Http\Response
     */
    public function edit(LegoItem $legoItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LegoItem  $legoItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LegoItem $legoItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LegoItem  $legoItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(LegoItem $legoItem)
    {
        //
    }
}
