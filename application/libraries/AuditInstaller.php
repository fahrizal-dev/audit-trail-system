<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuditInstaller {

    private $base_url = 'http://localhost/audit-trail/api/registerApp';

    public function register()
    {
        $payload = [
            'nama_aplikasi' => 'SIDORRS'
        ];

        $ch = curl_init($this->base_url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }
}
