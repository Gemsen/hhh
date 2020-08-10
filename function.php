<?php
class ydf
{
    const ApiUrl = 'https://goid.gojekapi.com';
    const Api2Url = 'https://api.gojekapi.com';
    const appId = 'com.go-jek.ios';
    const phoneModel = 'Apple, iPhone11,6';
    const phoneMake = 'Apple';
    const osDevice = 'iOS, 13.3.1';
    const xPlatform = 'iOS';
    const appVersion = '3.51';
    const clientId = 'gojek:consumer:app';
    const clientSecret = 'pGwQ7oi8bKqqwvid09UrjqpkMEHklb';
    const userAgent = 'Gojek/3.51 (com.go-jek.ios; build:6890866; iOS 13.3.1) Alamofire/3.51';

    private $authToken;
    private $pin;
    private $sessionId;
    private $uniqueId;
    
    public function __construct($token = false)
    {
        $this->sessionId = '78EB815C-6AE5-4969-A6B1-BE5EC893F7AA'; // generated from self::uuidv4();
        $this->uniqueId = '5C816FEA-D93E-4910-B672-978FCFA992F2'; // generated from self::uuidv4();
        
        if ($token) {
            $this->authToken = $token;
        }
    }
    
    public function uuidv4()
    {
        $data    = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return strtoupper(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
    }
    
    public function getAuthToken($otpToken, $otpCode)
    {
        $payload = array(
            'data' => array(
                'otp_token' => $otpToken,
                'otp' => $otpCode
            ) ,
            'client_id' => self::clientId,
            'grant_type' => 'otp',
            'client_secret' => self::clientSecret
        );

        return self::Request(self::ApiUrl . '/goid/token', $payload, self::buildHeaders());
    }

    public function loginRequest($phoneNumber)
    {
        $payload = array(
            'client_id' => self::clientId,
            'client_secret' => self::clientSecret,
            'country_code' => '+62',
            'phone_number' => $phoneNumber
        );

        return self::Request(self::ApiUrl . '/goid/login/request', $payload, self::buildHeaders());
    }

    public function claimPromo()
    {
        
        return self::Request(self::Api2Url . '/go-promotions/v1/promotions/enrollments', false, self::buildHeaders());
    }

    protected function buildHeaders()
    {
        $headers = array(
            'x-appid: ' . self::appId,
            'x-phonemodel: ' . self::phoneModel,
            'user-agent: ' . self::userAgent,
            'x-session-id: ' . $this->sessionId,
            'x-phonemake: ' . self::phoneMake,
            'x-uniqueid: ' . $this->uniqueId,
            'x-deviceos: ' . self::osDevice,
            'x-platform: ' . self::xPlatform,
            'x-appversion: ' . self::appVersion,
            'accept: */*',
            'content-type: application/json',
            'x-user-type: customer'
        );

        if (!empty($this->authToken)) {
            array_push($headers, 'Authorization: Bearer ' . $this->authToken);
        }
        if (!empty($this->pin)) {
            array_push($headers, 'pin: ' . $this->pin);
        }
        return $headers;
    }

    protected function Request($url, $post = false, $headers = false)
    {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ));

        if ($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getResponse($response, $key)
    {
        $json = json_decode($response, true);
        return $json['data'][$key];
    }
}
