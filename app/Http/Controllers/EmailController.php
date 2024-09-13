<?php
namespace App\Http\Controllers;

use App\Jobs\SendBulkEmail;
use Illuminate\Http\Request;
use App\Models\EmailData;

class EmailController extends Controller
{
    public function sendBulkEmails()
    {
        // Increase the maximum execution time for this script
        set_time_limit(300); // 300 seconds (5 minutes)


        $emails = EmailData::where('status', '1')->orderBy('id', 'DESC')->pluck('email');
        $emails->chunk(1000)->each(function ($chunk) {
            foreach ($chunk as $email) {
                SendBulkEmail::dispatch($email);
            }
            sleep(10); // pause between batches to avoid overloading the server
        });

        return redirect()->back()->with('success', 'Bulk emails are being sent.');
    }

    public function showForm()
    {
        return view('emails');
    }
}
