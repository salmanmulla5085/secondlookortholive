<?php

namespace App\Services;

class SendGridService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('SENDGRID_API_KEY');
    }

    public function sendEmail($to, $subject, $content)
    {
        $url = 'https://api.sendgrid.com/v3/mail/send';

        $data = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $to]
                    ],
                    'subject' => $subject,
                ],
            ],
            'content' => [
                [
                    'type' => 'text/html',
                    'value' => $content,
                ],
            ],
            'from' => [
                'email' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME'),
            ],
            'reply_to' => [
                'email' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME'),
            ],
        ];

        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        return $response;
    }
}
