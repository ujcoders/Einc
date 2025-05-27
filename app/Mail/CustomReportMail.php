<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->subject('🌟 Unlock Your Financial Future with Dhan!')
                    ->view('emails.custom_report')
                    ->text('emails.custom_report');
    }

}
