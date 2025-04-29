<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalSMSApi;
use Brevo\Client\Configuration;
use Illuminate\Support\Facades\Log;

class SMSService
{
    private Configuration $config;
    private TransactionalSMSApi $apiInstance;

    public function __construct()
    {
        $this->config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('app.BREVO_SMS_API_KEY'));

        $this->apiInstance = new TransactionalSMSApi(
            new \GuzzleHttp\Client(),
            $this->config
        );
    }

    public function send(string $to, string $message): void
    {
        $sendSms = new \Brevo\Client\Model\SendTransacSms();
        $sendSms['sender'] = config('app.BREVO_SMS_SENDER');
        $sendSms['recipient'] = '+' . $to;
        $sendSms['content'] = $message;
        $sendSms['unicodeEnabled'] = true;

        try {
            $result = $this->apiInstance->sendTransacSms($sendSms);
            Log::info('SMS sent to ' . $to . ' with message: ' . $message);
            Log::info($result);
        } catch (\Exception $e) {
            Log::error('Error sending SMS to ' . $to . ' with message: ' . $message);
            Log::error($e->getResponseBody());
        }
    }
}
