<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Exception;

class ImportClientsCommand extends Command
{
    protected $signature = 'clients:import-file {filename}';
    protected $description = 'Upload a client CSV file from public directory to the API endpoint';

    public function handle()
    {
        $filename = $this->argument('filename');
        $filepath = public_path($filename);

        if (!file_exists($filepath)) {
            $this->error("❌ File not found in public directory: {$filename}");
            return 1;
        }

        try {
            $response = Http::attach(
                'file',
                file_get_contents($filepath),
                basename($filepath)
            )->post('http://127.0.0.1:2323/api/clients/import');

            if ($response->successful()) {
                $this->info("✅ File '{$filename}' uploaded and imported successfully.");
            } else {
                $this->error("❌ Server responded with an error: " . $response->body());
            }
        } catch (Exception $e) {
            $this->error("❌ Exception occurred while uploading file: " . $e->getMessage());
        }

        return 0;
    }
}
