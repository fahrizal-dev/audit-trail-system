<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        if (!isset($this->db)) $this->load->database();
    }

    public function get_admin_usernames()
    {
        $rows = $this->db->select('username')->get('tb_admin')->result_array();
        if (empty($rows)) return [];
        return array_column($rows, 'username');
    }

    protected function apply_role_filter($role)
    {
        if ($role === 'admin') {
            $admins = $this->get_admin_usernames();
            if (!empty($admins)) {
                $this->db->group_start();
                $this->db->where_in('user', $admins);
                $this->db->group_end();
            } else {
                $this->db->where('1 = 0');
            }
        } elseif ($role === 'user') {
            $admins = $this->get_admin_usernames();
            if (!empty($admins)) {
                $this->db->group_start();
                $this->db->where_not_in('user', $admins);
                $this->db->or_where('user IS NULL', null, false);
                $this->db->or_where('user', '');
                $this->db->group_end();
            }
        }
    }

    public function get_activity_paginated(
        $user = null, $start = null, $end = null, $aksi = null, $ip = null,
        $hasil = null, $app = null, $q = null, $limit = 20, $offset = 0,
        $role = 'all', $sort = 'desc'
    ) {
        $this->db->from("tb_activity");

        if ($role && $role !== 'all') {
            $this->apply_role_filter($role);
        }

        if ($user)  $this->db->where("user", $user);
        if ($aksi)  $this->db->where("aksi", $aksi);
        if ($ip)    $this->db->where("ip_address", $ip);
        if ($hasil) $this->db->where("hasil", $hasil);
        if ($app)   $this->db->where("id_aplikasi", $app);

        if ($start) $this->db->where("modidate >=", $start . " 00:00:00");
        if ($end)   $this->db->where("modidate <=", $end . " 23:59:59");

        if ($q) {
            $this->db->group_start();
            $this->db->like("ket", $q);
            $this->db->or_like("trx_id", $q);
            $this->db->or_like("menu_fitur", $q);
            $this->db->group_end();
        }

        if ($sort === 'asc') {
            $this->db->order_by("modidate", "ASC");
        } else {
            $this->db->order_by("modidate", "DESC");
        }

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function count_activity($user = null, $start = null, $end = null, $aksi = null, $ip = null, $hasil = null, $app = null, $q = null, $role = 'all')
    {
        $this->db->from("tb_activity");

        if ($role && $role !== 'all') {
            $this->apply_role_filter($role);
        }

        if ($user)  $this->db->where("user", $user);
        if ($aksi)  $this->db->like("aksi", $aksi);
        if ($ip)    $this->db->where("ip_address", $ip);
        if ($hasil) $this->db->where("hasil", $hasil);
        if ($app)   $this->db->where("id_aplikasi", $app);

        if ($start) $this->db->where("modidate >=", $start . " 00:00:00");
        if ($end)   $this->db->where("modidate <=", $end . " 23:59:59");

        if ($q) {
            $this->db->group_start();
            $this->db->like("ket", $q);
            $this->db->or_like("trx_id", $q);
            $this->db->or_like("menu_fitur", $q);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_activity_all(
        $user = null, $start = null, $end = null, $aksi = null, $ip = null,
        $hasil = null, $app = null, $q = null, $role = 'all', $sort = 'desc'
    ) {
        $this->db->from("tb_activity");

        if ($role && $role !== 'all') {
            $this->apply_role_filter($role);
        }

        if ($user)  $this->db->where("user", $user);
        if ($aksi)  $this->db->like("aksi", $aksi);
        if ($ip)    $this->db->where("ip_address", $ip);
        if ($hasil) $this->db->where("hasil", $hasil);
        if ($app)   $this->db->where("id_aplikasi", $app);

        if ($start) $this->db->where("modidate >=", $start . " 00:00:00");
        if ($end)   $this->db->where("modidate <=", $end . " 23:59:59");

        if ($q) {
            $this->db->group_start();
            $this->db->like("ket", $q);
            $this->db->or_like("trx_id", $q);
            $this->db->or_like("menu_fitur", $q);
            $this->db->group_end();
        }

        if ($sort === 'asc') {
            $this->db->order_by("modidate", "ASC");
        } else {
            $this->db->order_by("modidate", "DESC");
        }

        return $this->db->get()->result();
    }

    protected function apply_log_app_filter($app = null)
    {
        if ($app === null || $app === '') {
            return;
        }

        $app = (int) $app;
        $has_direct_col = $this->db->field_exists('id_aplikasi', 'tb_log_api');

        $this->db->group_start();

        if ($has_direct_col) {
            $this->db->where('id_aplikasi', $app);
        }

        // Backward compatible for old logs or schema without id_aplikasi.
        $legacy_patterns = [
            '"id_aplikasi":' . $app,
            '"x-userid": "' . $app . '"',
            '"x-userid":"' . $app . '"'
        ];

        foreach ($legacy_patterns as $idx => $pattern) {
            if ($has_direct_col || $idx > 0) {
                $this->db->or_like('request', $pattern);
            } else {
                $this->db->like('request', $pattern);
            }
        }

        $this->db->group_end();
    }

    public function get_log_api_paginated($start = null, $end = null, $q = null, $app = null, $limit = 20, $offset = 0, $sort = 'desc')
    {
        $this->db->from("tb_log_api");

        if ($start) $this->db->where("waktu_akses >=", $start . " 00:00:00");
        if ($end)   $this->db->where("waktu_akses <=", $end . " 23:59:59");
        $this->apply_log_app_filter($app);

        if ($q) {
            $this->db->group_start();
            $this->db->like("request", $q);
            $this->db->or_like("response", $q);
            $this->db->group_end();
        }

        if ($sort === 'asc') {
            $this->db->order_by("waktu_akses", "ASC");
        } else {
            $this->db->order_by("waktu_akses", "DESC");
        }

        if ($limit !== null) $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_log_api($start = null, $end = null, $q = null, $app = null)
    {
        $this->db->from("tb_log_api");

        if ($start) $this->db->where("waktu_akses >=", $start . " 00:00:00");
        if ($end)   $this->db->where("waktu_akses <=", $end . " 23:59:59");
        $this->apply_log_app_filter($app);

        if ($q) {
            $this->db->group_start();
            $this->db->like("request", $q);
            $this->db->or_like("response", $q);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_log_api_all($start = null, $end = null, $q = null, $app = null, $sort = 'desc')
    {
        $this->db->from("tb_log_api");

        if ($start) $this->db->where("waktu_akses >=", $start . " 00:00:00");
        if ($end)   $this->db->where("waktu_akses <=", $end . " 23:59:59");
        $this->apply_log_app_filter($app);

        if ($q) {
            $this->db->group_start();
            $this->db->like("request", $q);
            $this->db->or_like("response", $q);
            $this->db->group_end();
        }

        if ($sort === 'asc') {
            $this->db->order_by("waktu_akses", "ASC");
        } else {
            $this->db->order_by("waktu_akses", "DESC");
        }

        return $this->db->get()->result();
    }

    public function log($data){
        $insert = [
            'id_aplikasi' => isset($data['id_aplikasi']) ? $data['id_aplikasi'] : null,
            'modidate'    => date('Y-m-d H:i:s'),
            'user'        => isset($data['username']) ? $data['username'] : null,
            'menu_fitur'  => isset($data['menu']) ? $data['menu'] : null,
            'no_rm'       => isset($data['no_rm']) ? $data['no_rm'] : null,
            'aksi'        => isset($data['aksi']) ? $data['aksi'] : null,
            'hasil'       => isset($data['status']) ? (is_string($data['status']) ? strtolower($data['status']) : $data['status']) : null,
            'trx_id'      => isset($data['trx_id']) ? $data['trx_id'] : null,
            'rawat'       => isset($data['rawat']) ? $data['rawat'] : null,
            'ip_address'  => isset($data['ip']) ? $data['ip'] : $this->input->ip_address(),
            'ket'         => isset($data['keterangan']) ? $data['keterangan'] : null
        ];

        return $this->db->insert('tb_activity', $insert);
    }

    public function get_admin_logs($limit, $offset, $filters = [])
    {
        $this->db->from('tb_activity');
        $this->db->where('menu_fitur', 'auth_admin');
        $this->db->order_by('id_activity', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_admin_logs()
    {
        $this->db->where('menu_fitur', 'auth_admin');
        return $this->db->count_all_results('tb_activity');
    }

    public function get_user_logs($limit, $offset, $filters = [])
    {
        $this->db->from('tb_activity');
        $this->db->where('menu_fitur !=', 'auth_admin');
        $this->db->order_by('id_activity', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_user_logs()
    {
        $this->db->where('menu_fitur !=', 'auth_admin');
        return $this->db->count_all_results('tb_activity');
    }

    public function get_activity_by_id($id)
    {
        return $this->db->where('id_activity', $id)->get('tb_activity')->row();
    }

    public function get_log_api_by_id($id)
    {
        return $this->db->where('id_log', $id)->get('tb_log_api')->row();
    }

    public function get_important_activity($limit = 50, $offset = 0)
    {
        $important_actions = ['LOGIN', 'LOGIN_FAIL', 'CREATE', 'UPDATE', 'DELETE'];

        $this->db->from('tb_activity');
        $this->db->where_in('aksi', $important_actions);
        $this->db->order_by('waktu', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_important_activity()
    {
        $important_actions = ['LOGIN', 'LOGIN_FAIL', 'CREATE', 'UPDATE', 'DELETE'];
        $this->db->from('tb_activity');
        $this->db->where_in('aksi', $important_actions);
        return $this->db->count_all_results();
    }

    // ======================================================
    // GET ALL APPS WITH ACTIVE TOKENS
    // ======================================================
    public function get_all_apps_with_tokens()
    {
        $this->db->select('
            a.id_aplikasi,
            a.NM_APLIKASI,
            a.user_name,
            a.secret_key,
            a.status_active,
            a.IP_ADDRESS,
            a.modidate,
            t.token,
            t.exp_date,
            t.use_date
        ');
        $this->db->from('tb_user_app a');
        $this->db->join('tb_token t', 'a.id_aplikasi = t.id_aplikasi', 'left');
        $this->db->order_by('a.NM_APLIKASI', 'ASC');
        $this->db->order_by('t.exp_date', 'DESC');
        
        $result = $this->db->get()->result();
        
        // Group tokens by app
        $apps = [];
        foreach ($result as $row) {
            $id = $row->id_aplikasi;
            
            if (!isset($apps[$id])) {
                $apps[$id] = (object)[
                    'id_aplikasi' => $row->id_aplikasi,
                    'NM_APLIKASI' => $row->NM_APLIKASI,
                    'user_name' => $row->user_name,
                    'secret_key' => $row->secret_key,
                    'status_active' => $row->status_active,
                    'IP_ADDRESS' => $row->IP_ADDRESS,
                    'modidate' => $row->modidate,
                    'tokens' => []
                ];
            }
            
            if ($row->token) {
                $apps[$id]->tokens[] = (object)[
                    'token' => $row->token,
                    'exp_date' => $row->exp_date,
                    'use_date' => $row->use_date
                ];
            }
        }
        
        return array_values($apps);
    }
}
