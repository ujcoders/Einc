<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class FetchVehicleRC extends Command
{
    protected $signature = 'fetch:rc {number}';
    protected $description = 'Fetch vehicle RC data from carinfo.app';

    public function handle()
    {
        $number = strtoupper($this->argument('number'));
        $url = "https://www.carinfo.app/rc-details/{$number}";

        $response = Http::get($url);

        if (!$response->ok()) {
            $this->error("Failed to fetch data. Status: " . $response->status());
            return;
        }

        $html = $response->body();

        $crawler = new Crawler($html);

        $data = [];

        try {
            // Sample data extraction – change these selectors based on actual HTML structure
            $crawler->filter('.card-body .table tbody tr')->each(function ($node) use (&$data) {
                $cols = $node->filter('td');

                if ($cols->count() >= 2) {
                    $key = trim($cols->eq(0)->text());
                    $value = trim($cols->eq(1)->text());
                    $data[$key] = $value;
                }
            });

            $this->info("Data for vehicle: {$number}");
            foreach ($data as $key => $value) {
                $this->line("{$key}: {$value}");
            }

        } catch (\Exception $e) {
            $this->error("Error parsing HTML: " . $e->getMessage());
        }
    }
}
