<?php

namespace Tests\Feature;

use App\Models\LegoItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LegoControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_should_return_200()
    {
        $response = $this->get('/api/legos');

        $response->assertStatus(200);
    }

    public function test_should_return_data()
    {
        $data = [[
            "marketplace" => "UK",
            "number" => 12345,
            "name" => "Fake name",
            "url" => "some url",
            "price" => 38292,
            "sale_price" => null
        ]];
        LegoItem::factory()->create($data[0]);

        $response = $this->get('/api/legos');

        $response
            ->assertJson($data)
            ->assertStatus(200);
    }
}
