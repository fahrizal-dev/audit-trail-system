<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

    //============================
    //GET USER BY APPLICATION ID
    //============================
    public function getUserById($id_aplikasi)
    {
        return $this->db->get_where("tb_user_app", [
            "id_aplikasi" => $id_aplikasi
        ])->row();
    }

    //============================
    //GET ALL APPS (for filter dropdown)
    //============================
    public function get_all_apps()
    {
        return $this->db->select('id_aplikasi, NM_APLIKASI, user_name, IP_ADDRESS, status_active')
                        ->from('tb_user_app')
                        ->order_by('NM_APLIKASI', 'ASC')
                        ->get()
                        ->result();
    }

    //============================
    //INSERT TOKEN
    //============================
    public function insertToken($data)
    {
        return $this->db->insert("tb_token", $data);
    }

    //============================
    //UPDATE TOKEN USE DATE
    //============================
    public function update_use_date($token)
    {
        $this->db->set("use_date", date('Y-m-d H:i:s'));
        $this->db->where("token", $token);
        return $this->db->update("tb_token");
    }

    //============================
    //UPDATE MODIBY & MODIDATE (ADMIN)
    //============================
    public function update_modiby_admin($username)
    {
        $this->db->set("modiby", $username);
        $this->db->set("modidate", date('Y-m-d H:i:s'));
        $this->db->where("username", $username);
        return $this->db->update("tb_admin");
    }

    //============================
    //UPDATE MODIBY & MODIDATE (USER APP)
    //============================
    public function update_modiby_user_app($id_aplikasi, $modiby)
    {
        $this->db->set("modiby", $modiby);
        $this->db->set("modidate", date('Y-m-d H:i:s'));
        $this->db->where("id_aplikasi", $id_aplikasi);
        return $this->db->update("tb_user_app");
    }

    //============================
    //INSERT ACTIVITY LOG
    //============================
    public function insert_activity($data)
    {
        if (!isset($data['modidate'])) {
            $data['modidate'] = date('Y-m-d H:i:s');
        }
        return $this->db->insert("tb_activity", $data);
    }

    //============================
    //INSERT LOG API
    //============================
    public function insert_log_api($method, $request, $response, $request_id = null, $duration_ms = null, $id_aplikasi = null)
    {
        $req = is_array($request) ? $request : $request;
        $resp = $response;

        $data = [
            "waktu_akses" => date('Y-m-d H:i:s'),
            "ip_address"  => $this->input->ip_address(),
            "metode"      => $method,
            "request"     => is_string($req) ? $req : json_encode($req),
            "response"    => is_string($resp) ? $resp : json_encode($resp)
        ];

        if ($this->db->field_exists('id_aplikasi', 'tb_log_api')) {
            $data['id_aplikasi'] = ($id_aplikasi === null || $id_aplikasi === '') ? null : (int) $id_aplikasi;
        }

        return $this->db->insert("tb_log_api", $data);
    }

    public function generate_activity_log($data = [])
    {
        $user        = $data['user']        ?? 'unknown';
        $menu        = $data['menu_fitur']  ?? '-';
        $aksi        = strtoupper($data['aksi'] ?? 'UNKNOWN');
        $hasil       = strtolower($data['hasil'] ?? 'success');
        $no_rm       = $data['no_rm']       ?? null;
        $trx_id      = $data['trx_id']      ?? null;
        $rawat       = strtoupper($data['rawat'] ?? null);
        $ip          = $data['ip_address']  ?? $_SERVER['REMOTE_ADDR'];
        $ket_custom  = $data['ket']         ?? null;

        if ($ket_custom) {
            $ket = $ket_custom;
        } else {

            $templates = [
                'CREATE' => "$user berhasil menambahkan data pada menu $menu",
                'UPDATE' => "$user berhasil mengubah data pada menu $menu",
                'DELETE' => "$user berhasil menghapus data pada menu $menu",
                'LOGIN'  => "$user berhasil login",
                'LOGOUT' => "$user berhasil logout",
                'FAILED' => "$user gagal melakukan aksi pada menu $menu",
                'API'    => "$user melakukan request API pada menu $menu",
                'UNKNOWN'=> "$user melakukan aksi pada menu $menu",
            ];

            $ket = $templates[$aksi] ?? $templates['UNKNOWN'];

            if ($no_rm) {
                $ket .= " (no_rm $no_rm)";
            }

            if ($rawat) {
                $ket .= " - Rawat $rawat";
            }

            if ($trx_id) {
                $ket .= " [trx_id: $trx_id]";
            }
        }

        $final = [
            "id_aplikasi" => $data["id_aplikasi"] ?? null,
            "user"        => $user,
            "menu_fitur"  => $menu,
            "aksi"        => $aksi,
            "hasil"       => $hasil,
            "no_rm"       => $no_rm,
            "trx_id"      => $trx_id,
            "rawat"       => $rawat,
            "ip_address"  => $ip,
            "ket"         => $ket,
            "modidate"    => date("Y-m-d H:i:s")
        ];

        return $this->db->insert("tb_activity", $final);
    }
}