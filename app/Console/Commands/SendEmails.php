<?php

namespace App\Console\Commands;

use Mail;
use Illuminate\Console\Command;
use Illuminate\Mail\Mailable;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('Please enter email address');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Wrong format!');
            return;
        }

        $message = $this->ask('Please enter message');

        $this->info('A email will be sent to ' . $email);

        Mail::raw('Test message', function ($message) use ($email) {
            /** @var Mailable $message */
            $message->to($email)
                ->subject('Test message');
        });
    }
}
