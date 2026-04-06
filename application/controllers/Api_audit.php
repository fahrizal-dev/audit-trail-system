<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_audit extends CI_Controller {

    protected $api_key = 'MYSECRET123';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Audit_model');
        $this->load->helper('crypto_helper');
    }

    public function log()
    {
        // ===== VALIDASI API KEY =====
        $incoming_key = $this->input->get_request_header('X-Audit-Key');

        if (
            !$incoming_key ||
            hash256($this->api_key) !== hash256($incoming_key)
        ) {
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => 'Invalid or missing API key'
                ]));
        }

        // ===== PARSE JSON BODY =====
        $raw  = file_get_contents("php://input");
        $data = json_decode($raw, true);

        if (!$data) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'error' => 'Invalid JSON'
                ]));
        }

        // ===== VALIDASI FIELD =====
        $required = ['id_user', 'aksi', 'keterangan'];
        foreach ($required as $r) {
            if (empty($data[$r])) {
                return $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'error' => "Missing field: $r"
                    ]));
            }
        }

        // ===== INSERT AUDIT =====
        $insert = [
            'id_user'    => $data['id_user'],
            'aksi'       => $data['aksi'],
            'keterangan' => $data['keterangan'],
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->Audit_model->log($insert);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => 'ok'
            ]));
    }
}
