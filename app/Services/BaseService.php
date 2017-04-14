<?php

namespace App\Services;

use GuzzleHttp\Client;

class BaseService
{
    const VCODE = null;
    const CHANNEL = 3031294;
    const MERCHANT = 3031293;
    const CASHIER = 3032240;

    public function __construct()
    {
        $this->smsInit();
    }

    private function smsInit()
    {
        $this->appKey = env('yx.appkey');
        $this->appSecret = env('yx.appsecret');
        $this->nonce = rand(100000, 999999);
        $this->curTime = time();
        $this->checkSum = strtolower(sha1($this->appSecret . $this->nonce . $this->curTime));
        $this->headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'charset' => 'utf-8',
            'AppKey' => $this->appKey,
            'Nonce' => $this->nonce,
            'CurTime' => $this->curTime,
            'CheckSum' => $this->checkSum,
        ];
    }

    /**
     * 发送验证码短信
     */
    protected function sendVcodeSMS($mobile)
    {
        $client = new Client();
        $response = $client->post('http://api.netease.im/sms/sendcode.action', [
            'headers' => $this->headers,
            'form_params' => [
                'mobile' => $mobile,
            ],
        ]);
        if ($response->getStatusCode() == 200) {
            return true;
        }

        return false;
    }

    /**
     * 发送模板短信
     */
    protected function sendTemplateSMS(int $tempId, array $mobiles, array $params)
    {
        $client = new Client();
        $response = $client->post('https://api.netease.im/sms/sendtemplate.action', [
            'headers' => $this->headers,
            'form_params' => [
                'templateid' => $tempId,
                'mobiles' => json_encode($mobiles),
                'params' => json_encode($params),
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return true;
        }

        return false;
    }
}
