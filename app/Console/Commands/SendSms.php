<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:send-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Sms';

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
     * @param SmsService $smsService
     */
    public function handle(SmsService $smsService)
    {
        $phone = $this->ask('Please enter phone number');

        $message = $this->ask('Please enter message');

        if (!$phone && !$message) {
            $this->error('Wrong Credentials Please enter!');
            return;
        }

        $this->info('A sms will be sent to ' . $phone);

        $smsService->send($smsService->clearPhone($phone), $message);
    }
}
