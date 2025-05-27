<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomReportMail;
use App\Models\Report as r;


class Report extends Command
{
    protected $signature = 'report1';
    protected $description = 'Send a custom test bank-style email';

    public function handle()
    {
        // $to = 'desainilesh1062@gmail.com';
        // $to = 'mumbaidigitalexperts@gmail.com';
        $to = 'ujwaldangi335789@gmail.com';

        try {
            Mail::to($to)->send(new CustomReportMail());

            r::create([
                'to_email' => $to,
                'user_name' => 'Nilesh',
                'server_name' => gethostname(),
                'ip_address' => gethostbyname(gethostname()),
                'host' => 'console',
                'sent_at' => now(),
                'delivered' => true,
                'status' => 'sent'
            ]);

            $this->info("✅ Email sent and report logged.");
        } catch (\Exception $e) {
            Report::create([
                'to_email' => $to,
                'user_name' => 'Nilesh',
                'server_name' => gethostname(),
                'ip_address' => gethostbyname(gethostname()),
                'host' => 'console',
                'sent_at' => now(),
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            $this->error("❌ Email failed: " . $e->getMessage());
        }
    }
}
