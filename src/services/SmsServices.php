<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

require_once __DIR__ . '/../../config/Twilio_config.local.php';

class SmsServices
{
    private Client $twilio;
    private string $serviceSid;

    public function __construct()
    {
        if (
            !defined('TWILIO_ACCOUNT_SID') ||
            !defined('TWILIO_AUTH_TOKEN') ||
            !defined('TWILIO_SERVICE_SID')
        ) {
            throw new \Exception('Twilio configuration missing');
        }

        $this->twilio     = new Client(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);
        $this->serviceSid = TWILIO_SERVICE_SID;
    }

    private function formatMobile(string $mobile): string
    {
        $mobile = preg_replace('/\D/', '', $mobile);

        if (strlen($mobile) === 10) {
            return '+91' . $mobile;
        }

        if (strlen($mobile) === 12 && str_starts_with($mobile, '91')) {
            return '+' . $mobile;
        }

        if (str_starts_with($mobile, '+')) {
            return $mobile;
        }

        throw new \Exception('Invalid mobile number');
    }

    public function sendOtp(string $mobile): bool
    {
        try {
            $mobile = $this->formatMobile($mobile);
            
            $verification = $this->twilio->verify->v2
                ->services($this->serviceSid)
                ->verifications
                ->create($mobile, 'sms');

            error_log('OTP SEND → ' . json_encode([
                'to' => $verification->to,
                'status' => $verification->status,
                'sid' => $verification->sid
            ]));    

            return $verification->status === 'pending';

        } catch (TwilioException $e) {
            error_log('Twilio Send OTP Error: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyOtp(string $mobile, string $otp): bool
{
    try {
        $mobile = $this->formatMobile($mobile);

        $check = $this->twilio->verify->v2
            ->services($this->serviceSid)
            ->verificationChecks
            ->create([
                'to'   => $mobile,
                'code' => $otp
            ]);

        error_log("VERIFY OTP → Mobile: $mobile | Code: $otp | Status: {$check->status}");

        return $check->status === 'approved';

    } catch (TwilioException $e) {
        error_log('Twilio Verify OTP Error: ' . $e->getMessage());
        return false;
    }
}


}
