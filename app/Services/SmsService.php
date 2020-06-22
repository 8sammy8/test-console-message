<?php

namespace App\Services;

class SmsService
{
    /**
     * SmsService for https://playmobile.uz/
     */

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $login = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * The maximum number of messages in one request.
     *
     * @var int
     */
    protected $maxMessages = 500;

    /**
     * Messages for mass mailing.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Add a message to the mass mailing.
     *
     * @param $phone
     * @param $message
     */
    public function add($phone, $message) {
        $this->messages[] = ['phone' => $phone, 'message' => $message];
    }

    /**
     * Sends one sms message.
     *
     * @param $phone
     * @param $message
     * @return mixed
     */
    public function send($phone, $message)
    {
        $request = $this->makeRequest([['phone' => $phone, 'message' => $message]]);
        return $this->request($request);
    }

    /**
     * Send all messages from the mass mailing.
     */
    public function sendAll()
    {
        $chunks = array_chunk($this->messages, $this->maxMessages);

        foreach ($chunks as $messages) {
            $request = $this->makeRequest($messages);
            $this->request($request);
        }
    }

    /**
     * Submit a request.
     *
     * @param $data
     * @return bool|string
     */
    protected function request($data)
    {
        if ($data)
        {
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->login . ":" . $this->password);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "$data");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }
        return false;
    }

    /**
     * Make a request.
     *
     * @param array $messages
     * @return bool|false|string
     */
    protected function makeRequest(array $messages)
    {
        foreach ($messages as $message) {
            $request = [
                "messages" => [
                    "recipient" => $message['phone'],
                    "message-id" => time() . \Str::random('4'),
                    "sms" => [
                        "originator" => "3700",
                        "content" => [
                            "text" => $message['message']
                        ]
                    ]
                ]
            ];
        }

        return isset($request) ? json_encode($request) : false;
    }

    /**
     * Deletes all characters except numbers from the phone number.
     *
     * @param string $phone
     * @return string
     */
    public function clearPhone($phone)
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
