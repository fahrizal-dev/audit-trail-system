<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['security', 'crypto']); // crypto_helper
        $this->load->model('Api_auth_model');
        header('Content-Type: application/json');
    }

    /**
     * =====================================
     * POST /api/auth/app-login
     * =====================================
     * Body:
     * {
     *   "username": "sidorrs_app",
     *   "password": "password_aplikasi",
     *   "secret_key": "SECRET_KEY"
     * }
     */
    public function app_login()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            return $this->_response(false, 'Method not allowed', 405);
        }

        $input = json_decode($this->input->raw_input_stream, true);

        $username   = trim($input['username'] ?? '');
        $password   = $input['password'] ?? '';
        $secret_key = $input['secret_key'] ?? '';
        $ip         = $this->input->ip_address();

        if (!$username || !$password || !$secret_key) {
            return $this->_response(false, 'Parameter tidak lengkap', 400);
        }

        // ==============================
        // Cari aplikasi
        // ==============================
        $app = $this->Api_auth_model->get_app_by_username($username);

        if (!$app || (int)$app->status_active !== 1) {
            return $this->_response(false, 'Aplikasi tidak valid', 401);
        }

        // ==============================
        // Validasi password
        // ==============================
        if (hash256($password) !== $app->password) {
            return $this->_response(false, 'Autentikasi gagal', 401);
        }

        // ==============================
        // Validasi secret key
        // ==============================
        if (!hash_equals($app->secret_key, $secret_key)) {
            return $this->_response(false, 'Secret key salah', 401);
        }

        // ==============================
        // Generate token
        // ==============================
        $expired_at = date('Y-m-d H:i:s', strtotime('+6 hours'));
        $raw_token  = $app->id_aplikasi . '|' . time() . '|' . random_bytes(16);
        $token      = hash256($raw_token);

        // ==============================
        // Simpan token
        // ==============================
        $this->db->insert('tb_token', [
            'id_aplikasi' => $app->id_aplikasi,
            'token'       => $token,
            'use_date'    => date('Y-m-d H:i:s'),
            'exp_date'    => $expired_at,
            'ip_address'  => $ip
        ]);

        return $this->_response(true, 'Login aplikasi berhasil', 200, [
            'token'      => $token,
            'expired_at'=> $expired_at
        ]);
    }

    // =====================================
    // Helper JSON Response
    // =====================================
    private function _response($status, $message, $code = 200, $data = null)
    {
        http_response_code($code);
        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ], JSON_PRETTY_PRINT);
        exit;
    }
}
