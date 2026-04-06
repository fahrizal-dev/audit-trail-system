<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    private $secret_key = "MYSECRETKEY123";
    private $raw_input_stream;

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->raw_input_stream = file_get_contents("php://input");

        $this->load->database();
        $this->load->model('Api_model');
        $this->load->helper('crypto_helper');
        $this->load->config('audit_config');
    }

    private function generate_request_id() {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    private function normalize_action($method) {
        $method = strtoupper($method);
        $method = str_replace("TOKEN", " TOKEN", $method);
        return trim($method);
    }

    private function generateKet(array $activity) {
        $user = $activity["user"] ?? "User";
        $aksi = strtoupper($activity["aksi"] ?? "");
        $menu = $activity["menu_fitur"] ?? "menu";
        $kolom = $activity["kolom"] ?? null;

        switch ($aksi) {
            case "UPDATE":
            case "EDIT":
                if ($kolom) return "{$user} berhasil melakukan update pada kolom {$kolom}";
                return "{$user} berhasil melakukan update pada {$menu}";
            case "CREATE":
            case "ADD":
                return "{$user} berhasil menambahkan data pada {$menu}";
            case "DELETE":
            case "REMOVE":
                return "{$user} berhasil menghapus data pada {$menu}";
            case "LOGIN":
                return "{$user} berhasil login ke sistem";
            case "LOGOUT":
                return "{$user} telah logout dari sistem";
            default:
                if ($aksi) return "{$user} melakukan aksi {$aksi} pada {$menu}";
                return "{$user} melakukan aktivitas pada {$menu}";
        }
    }

    // ======================================================
    // 1. GET TOKEN
    // ======================================================
    public function getToken() {
        $start_time = microtime(true);
        $request_id = $this->generate_request_id();

        $userid   = $this->input->get_request_header('x-userid', TRUE);
        $password = $this->input->get_request_header('x-password', TRUE);

        if (!$userid || !$password) {
            return $this->jsonResponse(null, "Header x-userid dan x-password wajib diisi", 400, null, $request_id, $start_time);
        }

        $user = $this->Api_model->getUserById($userid);
        if (!$user) {
            return $this->jsonResponse(null, "User tidak ditemukan", 401, null, $request_id, $start_time);
        }

        // password
        $stored = $user->password;
        $password_ok = false;

        if (strlen($stored) === 64 && ctype_xdigit($stored)) {
            if (hash('sha256', $password) === $stored) $password_ok = true;
        } else {
            if (password_verify($password, $stored)) $password_ok = true;
        }

        if (!$password_ok) {
            return $this->jsonResponse(null, "User atau Password salah", 401, null, $request_id, $start_time);
        }

        // token
        $this->secret_key = $user->secret_key;
        $token = bin2hex(random_bytes(32));
        
        $token_expiry = $this->config->item('audit_token_expiry') ?: 15;

        $data = [
            "id_aplikasi" => $userid,
            "token"       => $token,
            "modidate"    => date("Y-m-d H:i:s"),
            "exp_date"    => date("Y-m-d H:i:s", strtotime("+{$token_expiry} minutes")),
            "use_date"    => null
        ];

        $this->Api_model->insertToken($data);
        $this->Api_model->update_modiby_user_app($userid, $userid);

        $extra_activity = [
            "id_aplikasi" => $userid,
            "user"        => $user->user_name,
            "aksi"        => "CREATE",
            "hasil"       => "success",
            "ip_address"  => $this->input->ip_address(),
            "ket"         => "Permintaan token autentikasi berhasil diproses",
            "modidate"    => date("Y-m-d H:i:s")
        ];

        return $this->jsonResponse(["token" => $token], "OK", 200, $extra_activity, $request_id, $start_time);
    }

    // ======================================================
    // 2. POST TOKEN
    // ======================================================
    public function postToken() {
        $start_time = microtime(true);
        $request_id = $this->generate_request_id();

        $token = $this->input->get_request_header('x-token', TRUE);
        if (!$token) {
            return $this->jsonResponse(null, "Token tidak boleh kosong", 401, null, $request_id, $start_time);
        }

        $cek = $this->db->get_where("tb_token", ["token" => $token])->row();
        if (!$cek) {
            return $this->jsonResponse(null, "Token tidak valid", 401, null, $request_id, $start_time);
        }

        if (strtotime($cek->exp_date) < time()) {
            return $this->jsonResponse(null, "Token expired", 401, null, $request_id, $start_time);
        }

        $this->Api_model->update_use_date($token);

        // Auto-extend token if enabled
        if ($this->config->item('audit_token_auto_extend') !== FALSE) {
            $token_expiry = $this->config->item('audit_token_expiry') ?: 15;
            $this->db->set("exp_date", date("Y-m-d H:i:s", strtotime("+{$token_expiry} minutes")));
            $this->db->where("token", $token);
            $this->db->update("tb_token");
        }

        $user = $this->Api_model->getUserById($cek->id_aplikasi);
        if (!$user) {
            return $this->jsonResponse(null, "User aplikasi terkait token tidak ditemukan", 401, null, $request_id, $start_time);
        }

        $this->secret_key = $user->secret_key;

        $raw_json = $this->raw_input_stream;
        $json = json_decode($raw_json, true);

        if (is_array($json) && isset($json["data"]) && !isset($json["payload"])) {
            $plain = json_encode(["data" => $json["data"]]);
            $cipherText = rc4($this->secret_key, $plain);
            $payload = base64_encode($cipherText);
            $signature = hash_hmac("sha256", $payload, $this->secret_key);

            $json = [
                "payload"   => $payload,
                "signature" => $signature
            ];
        }

        if (!isset($json["payload"]) || !isset($json["signature"])) {
            return $this->jsonResponse(null, "Format JSON salah", 400, null, $request_id, $start_time);
        }

        $expected_sig = hash_hmac("sha256", $json["payload"], $this->secret_key);
        if (!hash_equals($expected_sig, $json["signature"])) {
            return $this->jsonResponse(null, "Signature tidak valid", 401, null, $request_id, $start_time);
        }

        // decrypt RC4
        $cipherText = base64_decode($json["payload"]);
        $plainText  = rc4($this->secret_key, $cipherText);
        $data = json_decode($plainText, true);

        if (!isset($data["data"])) {
            return $this->jsonResponse(null, "Format payload salah", 400, null, $request_id, $start_time);
        }

        $activity = $data["data"];
        $activity_db = [
            "id_aplikasi" => $cek->id_aplikasi,
            "user"        => $activity["user"] ?? $user->user_name,
            "menu_fitur"  => $activity["menu_fitur"] ?? null,
            "no_rm"       => $activity["no_rm"] ?? null,
            "aksi"        => isset($activity["aksi"]) ? strtoupper($activity["aksi"]) : "API_CALL",
            "hasil"       => $activity["hasil"] ?? "success",
            "trx_id"      => $activity["trx_id"] ?? null,
            "rawat"       => $activity["rawat"] ?? null,
            "ip_address"  => $activity["ip_address"] ?? $this->input->ip_address(),
            "ket"         => $activity["ket"] ?? $activity["keterangan"] ?? $this->generateKet($activity),
            "modidate"    => date("Y-m-d H:i:s")
        ];

        $this->Api_model->generate_activity_log($activity_db);
        $this->Api_model->update_modiby_user_app($cek->id_aplikasi, $cek->id_aplikasi);

        return $this->jsonResponse("Sukses Menyimpan Data", "Sukses", 200, null, $request_id, $start_time);
    }

    // ======================================================
    // JSON RESPONSE
    // ======================================================
    private function jsonResponse($response, $message, $code, $extra_activity = null, $request_id = null, $start_time = null) {
        $extra_activity = $extra_activity ?? null;

        $log_app_id = null;
        $userid_header = $this->input->get_request_header('x-userid', TRUE);
        if ($userid_header !== null && $userid_header !== '') {
            $log_app_id = (int) $userid_header;
        }

        if ($log_app_id === null) {
            $token_header = $this->input->get_request_header('x-token', TRUE);
            if ($token_header) {
                $token_row = $this->db->get_where('tb_token', ['token' => $token_header])->row();
                if ($token_row && isset($token_row->id_aplikasi)) {
                    $log_app_id = (int) $token_row->id_aplikasi;
                }
            }
        }

        $out = [
            "response" => $response,
            "metadata" => [
                "message" => $message,
                "code" => $code
            ]
        ];

        if ($start_time !== null) {
            $out["metadata"]["duration_ms"] = (int)((microtime(true) - $start_time) * 1000);
        }

        $method = strtoupper($this->router->fetch_method());

        $raw_request = $this->raw_input_stream;
        $req_decoded = json_decode($raw_request, true);

        $request_to_store = $req_decoded ?: (is_string($raw_request) ? $raw_request : null);

        if (is_array($request_to_store) && $log_app_id !== null) {
            $request_to_store['__audit'] = ['id_aplikasi' => $log_app_id];
        }

        if (empty($request_to_store) || (is_string($request_to_store) && trim($request_to_store) === "")) {
            $masked_password = $this->input->get_request_header('x-password') ? '*' : null;

            $placeholder = [
                "method" => $this->input->server('REQUEST_METHOD'),
                "headers" => [
                    "x-userid"   => $this->input->get_request_header('x-userid', TRUE),
                    "x-password" => $masked_password
                ],
                "note" => "No body sent — placeholder to avoid empty request log"
            ];

            $request_to_store = json_encode($placeholder, JSON_PRETTY_PRINT);
        }

        $this->Api_model->insert_log_api(
            $method,
            $request_to_store,
            $out,
            $request_id,
            $out["metadata"]["duration_ms"] ?? null,
            $log_app_id
        );

        if ($extra_activity) {
            $extra_activity['aksi'] = isset($extra_activity['aksi']) ? strtoupper($extra_activity['aksi']) : ($extra_activity['aksi'] ?? null);
            $this->Api_model->generate_activity_log($extra_activity);
        } else {
            $token = $this->input->get_request_header('x-token', TRUE);

            if ($token) {
                $cek = $this->db->get_where("tb_token", ["token" => $token])->row();

                if ($cek) {
                    if ($code != 200) {
                        $userObj = $this->Api_model->getUserById($cek->id_aplikasi);
                        $userName = $userObj ? $userObj->user_name : $cek->id_aplikasi;

                        $action_name = (strpos(strtolower($message), 'expired') !== false) ? 'TOKEN EXPIRED' : 'TOKEN INVALID';

                        $activity = [
                            "id_aplikasi" => $cek->id_aplikasi,
                            "user"        => $userName,
                            "aksi"        => $action_name,
                            "hasil"       => "failed",
                            "ip_address"  => $this->input->ip_address(),
                            "ket"         => $message,
                            "modidate"    => date("Y-m-d H:i:s")
                        ];
                        $this->Api_model->generate_activity_log($activity);
                    }
                } else {
                    
                    if (
                        $code != 200 &&
                        $this->router->fetch_method() === 'postToken'
                    ) {
                        $activity = [
                            "id_aplikasi" => null,
                            "user"        => null,
                            "aksi"        => "TOKEN INVALID",
                            "hasil"       => "failed",
                            "ip_address"  => $this->input->ip_address(),
                            "ket"         => $message,
                            "modidate"    => date("Y-m-d H:i:s")
                        ];
                        $this->Api_model->generate_activity_log($activity);
                    }
                }
            } else {
                if ($code != 200) {
                    $activity = [
                        "id_aplikasi" => null,
                        "user"        => null,
                        "aksi"        => "TOKEN MISSING",
                        "hasil"       => "failed",
                        "ip_address"  => $this->input->ip_address(),
                        "ket"         => $message,
                        "modidate"    => date("Y-m-d H:i:s")
                    ];
                    $this->Api_model->generate_activity_log($activity);
                }
            }
        }

        return $this->output
            ->set_content_type("application/json")
            ->set_output(json_encode($out));
    }

    public function registerApp()
    {
        $input = json_decode($this->raw_input_stream, true);

        if (!$input) {
            return $this->jsonResponse(null, "Invalid JSON", 400);
        }

        if (empty($input['nm_aplikasi']) || empty($input['user_name'])) {
            return $this->jsonResponse(null, "nm_aplikasi dan user_name wajib", 400);
        }

        // cek sudah ada
        $cek = $this->db->get_where('tb_user_app', [
            'user_name' => $input['user_name']
        ])->row();

        if ($cek) {
            return $this->jsonResponse([
                'id_aplikasi' => $cek->id_aplikasi,
                'user_name'   => $cek->user_name,
                'secret_key'  => $cek->secret_key
            ], "Aplikasi sudah terdaftar", 200);
        }

        $secret = bin2hex(random_bytes(16));
        $password_plain = $input['password'] ?? 'default123';
        $password_hash  = hash('sha256', $password_plain);

        $data = [
            'id_aplikasi'  => (int)$this->db->select_max('id_aplikasi')->get('tb_user_app')->row()->id_aplikasi + 1,
            'NM_APLIKASI'  => $input['nm_aplikasi'],
            'user_name'    => $input['user_name'],
            'password'     => $password_hash,
            'secret_key'   => $secret,
            'status_active'=> 1,
            'modiby'       => 'SYSTEM',
            'modidate'     => date('Y-m-d H:i:s'),
            'IP_ADDRESS'   => $this->input->ip_address()
        ];

        $this->db->insert('tb_user_app', $data);
    $id = $data['id_aplikasi'];

        return $this->jsonResponse([
            'id_aplikasi' => $id,
            'user_name'   => $input['user_name'],
            'password'    => $password_plain,
            'secret_key'  => $secret
        ], "Registrasi aplikasi berhasil", 200);
    }
}