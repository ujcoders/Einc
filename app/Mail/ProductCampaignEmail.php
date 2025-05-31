<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductCampaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $htmlContent;
    public $subjectLine;

    public function __construct($htmlContent, $subjectLine)
    {
        $this->htmlContent = $htmlContent;
        $this->subjectLine = $subjectLine;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.product_campaign');
    }
}
