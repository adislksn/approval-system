<?php

namespace App\Jobs;

use App\Helpers\MailHelpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MailerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $images, $user, $record;

    /**
     * Create a new job instance.
     */
    public function __construct($images, $user, $record)
    {
        $this->images = $images;
        $this->user = $user;
        $this->record = $record;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        MailHelpers::sendPDF($this->images, $this->user, $this->record);
    }
}
