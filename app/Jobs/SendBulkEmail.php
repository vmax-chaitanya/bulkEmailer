<?php 

namespace App\Jobs;

use App\Mail\BulkEmail;
use App\Models\EmailData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class SendBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        RateLimiter::attempt(
            'send-email:' . $this->email,
            1, // Number of allowed attempts
            function () {
                try {
                    Mail::to($this->email)->send(new BulkEmail());
                    Log::info('Email sent to ' . $this->email);
                    return true;
                } catch (\Exception $e) {
                    Log::error('Failed to send email to ' . $this->email . ': ' . $e->getMessage());

                     // Update the status to '0' for failed emails
                     EmailData::where('email', $this->email)
                     ->update(['status' => '0']);
                    return false;
                }
            },
            60 // Time window in seconds
        );
    }
}
