<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LegoScrapper extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_should_run_scraper_without_errors_for_us()
    {
        $this->artisan('scrape:lego --market=us --silent')
            ->expectsOutput('Getting data from https://www.lego.com/en-us/categories/retiring-soon')
            ->expectsOutput("Crawling results...")
            ->expectsOutput("Merging result set with database...")
            ->expectsOutput("Lego data scraped for us market")
            ->assertExitCode(0);
    }

    public function test_should_run_scraper_without_errors_for_uk()
    {
        $this->artisan('scrape:lego --market=uk --silent')
            ->expectsOutput('Getting data from https://www.lego.com/en-gb/categories/retiring-soon')
            ->expectsOutput("Crawling results...")
            ->expectsOutput("Merging result set with database...")
            ->expectsOutput("Lego data scraped for uk market")
            ->assertExitCode(0);
    }

    public function test_should_not_send_mail_when_silent_for_uk()
    {
        Mail::fake();
        $this->artisan('scrape:lego --market=uk --silent')
            ->expectsOutput('Getting data from https://www.lego.com/en-gb/categories/retiring-soon')
            ->expectsOutput("Crawling results...")
            ->expectsOutput("Merging result set with database...")
            ->expectsOutput("Lego data scraped for uk market")
            ->assertExitCode(0);
        Mail::assertNothingSent();
    }

    public function test_should_not_send_mail_when_silent_for_us()
    {
        Mail::fake();
        $this->artisan('scrape:lego --market=us --silent')
            ->expectsOutput('Getting data from https://www.lego.com/en-us/categories/retiring-soon')
            ->expectsOutput("Crawling results...")
            ->expectsOutput("Merging result set with database...")
            ->expectsOutput("Lego data scraped for us market")
            ->assertExitCode(0);
        Mail::assertNothingSent();
    }
}
