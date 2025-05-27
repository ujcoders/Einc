<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomReportMail;
use App\Models\Report as r;
use App\Models\Client; // make sure this matches your source table

class Report extends Command
{
    protected $signature = 'report';
    protected $description = 'Send bank-style email to all clients and log reports';

    public function handle()
    {
        // $clients = Client::whereNotNull('EMAIL_ID')->where('CLIENT_ID', 1003)->get(); // filter only those with email
        $clients = Client::whereNotNull('EMAIL_ID')->get(); // filter only those with email

        foreach ($clients as $client) {
            $to = $client->EMAIL_ID;
            $name = $client->client_name ?? 'Client';

            try {
                Mail::to($to)->send(new CustomReportMail($name)); // Pass name if needed in Mailable

                r::create([
                    'to_email'    => $to,
                    'user_name'   => $name,
                    'server_name' => gethostname(),
                    'ip_address'  => gethostbyname(gethostname()),
                    'host'        => 'console',
                    'sent_at'     => now(),
                    'delivered'   => true,
                    'status'      => 'sent'
                ]);

                $this->info("✅ Email sent to {$to} and logged.");
            } catch (\Exception $e) {
                r::create([
                    'to_email'      => $to,
                    'user_name'     => $name,
                    'server_name'   => gethostname(),
                    'ip_address'    => gethostbyname(gethostname()),
                    'host'          => 'console',
                    'sent_at'       => now(),
                    'status'        => 'failed',
                    'error_message' => $e->getMessage()
                ]);

                $this->error("❌ Failed to send to {$to}: " . $e->getMessage());
            }
        }

        $this->info("✅ All emails processed.");
    }
}
