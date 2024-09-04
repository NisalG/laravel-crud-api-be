<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail; 
use App\Mail\DailyPostCountEmail;

class SendDailyPostCountEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-post-count-email {to} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Daily Post Count Email: Command runs every minute to send Daily Post Count Email.');

        // See Jobs for emailing
        // Mail::send('post-create-email.template', $this->emailData, function ($message) {
        //     $message->to($this->emailData['to'])->subject($this->emailData['subject']);
        // });

        $to = $this->argument('to');
        $name = $this->argument('name');
        // $subject = $this->argument('subject');
        // $body = $this->argument('body');
        $postCount = 10; // Replace with post count from model

        $emailData = [
            'title' => 'Advance Laravel 11 API BE - Daily Post Count for Admin by Scheduling a Console Command',
            'body' => 'This email body is for testing purposes',
            'name' => $name,
            'postCount' => $postCount
        ];

        // Send email using Mail facade or any other email library
        Mail::to($to)->send(new DailyPostCountEmail($emailData));
    }
}
