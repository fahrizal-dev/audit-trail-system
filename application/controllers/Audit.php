<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Audit_model');
        $this->load->model('Api_model');
        $this->load->database();
        $this->load->helper(['download','url','security']);
        $this->load->library('session');

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");

        if (!$this->session->userdata('admin_logged_in')){
            redirect('auth/login');
        }
    }

    public function index()
    {
        redirect('audit/home');
    }

    public function home()
    {
        $this->load->view('audit/home');
    }

    public function activity()
    {
        // filters
        $user      = $this->input->get('user');
        $startdate = $this->input->get('start');
        $enddate   = $this->input->get('end');
        $aksi      = $this->input->get('aksi');
        $ip        = $this->input->get('ip');
        $hasil     = $this->input->get('hasil');
        $app       = $this->input->get('app');
        $q         = $this->input->get('q');
        $sort      = $this->input->get('sort') ?: 'desc';
        $show      = $this->input->get('show') ?: '10';

        $page = max(1, (int)$this->input->get('page'));
        $per_page = ($show === 'all') ? 0 : 10; 
        $offset = ($page - 1) * max(1, $per_page);

        // Count
        $data['total_activity'] = $this->Audit_model->count_activity($user, $startdate, $enddate, $aksi, $ip, $hasil, $app, $q, 'all');

        $limit_for_model = ($per_page === 0) ? null : $per_page;

        $data['activity'] = $this->Audit_model->get_activity_paginated(
            $user, $startdate, $enddate, $aksi, $ip, $hasil, $app, $q,
            $limit_for_model, $offset, 'all', $sort
        );

        // view filters/meta
        $data['filter_user']  = $user;
        $data['filter_start'] = $startdate;
        $data['filter_end']   = $enddate;
        $data['filter_aksi']  = $aksi;
        $data['filter_ip']    = $ip;
        $data['filter_hasil'] = $hasil;
        $data['filter_app']   = $app;
        $data['filter_q']     = $q;
        $data['filter_sort']  = $sort;
        $data['filter_show']  = $show;
        $data['filter_sort']  = $sort;
        $data['apps']         = $this->Api_model->get_all_apps();

        $data['page'] = $page;
        $data['per_page'] = ($per_page === 0) ? $data['total_activity'] : $per_page;

        $this->load->view('audit/activity', $data);
    }

    public function api_log()
    {
        $startdate = $this->input->get('start');
        $enddate   = $this->input->get('end');
        $q         = $this->input->get('q');
        $app       = $this->input->get('app');
        $sort      = $this->input->get('sort') ?: 'desc';
        $show      = $this->input->get('show') ?: '10';

        $page = max(1, (int)$this->input->get('page'));
        $per_page = ($show === 'all') ? 0 : 10;
        $offset = ($page - 1) * max(1, $per_page);

        $data['total_log'] = $this->Audit_model->count_log_api($startdate, $enddate, $q, $app);

        $limit_for_model = ($per_page === 0) ? null : $per_page;
        $data['log_api'] = $this->Audit_model->get_log_api_paginated(
            $startdate, $enddate, $q, $app, $limit_for_model, $offset, $sort
        );

        $data['filter_start'] = $startdate;
        $data['filter_end']   = $enddate;
        $data['filter_q']     = $q;
        $data['filter_app']   = $app;
        $data['filter_show']  = $show;
        $data['apps']         = $this->Api_model->get_all_apps();

        $data['page'] = $page;
        $data['per_page'] = ($per_page === 0) ? $data['total_log'] : $per_page;

        $this->load->view('audit/api_log', $data);
    }

    public function export_activity_csv()
    {
        $user      = $this->input->get('user');
        $startdate = $this->input->get('start');
        $enddate   = $this->input->get('end');
        $aksi      = $this->input->get('aksi');
        $ip        = $this->input->get('ip');
        $hasil     = $this->input->get('hasil');
        $app       = $this->input->get('app');
        $q         = $this->input->get('q');
        $role      = $this->input->get('role') ?? 'all';
        $sort      = $this->input->get('sort') ?: 'desc';

        $rows = $this->Audit_model->get_activity_all($user, $startdate, $enddate, $aksi, $ip, $hasil, $app, $q, $role, $sort);

        $csv = "id_activity,user,aksi,menu_fitur,ip_address,modidate,hasil,ket,id_aplikasi,trx_id,no_rm,rawat\n";
        foreach ($rows as $r) {
            $line = [
                $r->id_activity,
                $r->user,
                $r->aksi,
                str_replace(",", " ", $r->menu_fitur),
                $r->ip_address,
                $r->modidate,
                $r->hasil,
                str_replace(",", " ", strip_tags($r->ket)),
                isset($r->id_aplikasi) ? $r->id_aplikasi : '',
                isset($r->trx_id) ? $r->trx_id : '',
                isset($r->no_rm) ? $r->no_rm : '',
                isset($r->rawat) ? $r->rawat : ''
            ];
            $csv .= implode(",", $line) . "\n";
        }

        $filename = "activity_export_" . date("Ymd_His") . ".csv";
        force_download($filename, $csv);
    }

    public function export_log_api_csv()
    {
        $startdate = $this->input->get('start');
        $enddate   = $this->input->get('end');
        $q         = $this->input->get('q');
        $app       = $this->input->get('app');
        $sort      = $this->input->get('sort') ?: 'desc';

        $rows = $this->Audit_model->get_log_api_all($startdate, $enddate, $q, $app, $sort);

        $csv = "id_log,waktu_akses,ip_address,metode,request,response\n";
        foreach ($rows as $r) {

            $req_clean = preg_replace('/\s+/', ' ', trim($r->request));
            $res_clean = preg_replace('/\s+/', ' ', trim($r->response));

            $req = '"' . str_replace('"', '""', $req_clean) . '"';
            $res = '"' . str_replace('"', '""', $res_clean) . '"';

            $csv .= "{$r->id_log},{$r->waktu_akses},{$r->ip_address},{$r->metode},{$req},{$res}\n";
        }

        $filename = "log_api_export_" . date("Ymd_His") . ".csv";
        force_download($filename, $csv);
    }

    public function ajax_activity_detail()
    {
        $id = (int)$this->input->get('id');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'invalid id']);
            return;
        }
        $row = $this->Audit_model->get_activity_by_id($id);
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'not found']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $row]);
    }

    public function ajax_log_detail()
    {
        $id = (int)$this->input->get('id');
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'invalid id']);
            return;
        }
        $row = $this->Audit_model->get_log_api_by_id($id);
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'not found']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $row]);
    }

    public function ajax_refresh_tables()
    {
        $filters = $this->input->get();

        $page      = isset($filters['page']) ? intval($filters['page']) : 1;
        $per_page  = isset($filters['per_page']) ? intval($filters['per_page']) : 10;

        $activity = $this->Audit_model->get_activity_paginated(
            $filters['user'] ?? null,
            $filters['start'] ?? null,
            $filters['end'] ?? null,
            $filters['aksi'] ?? null,
            $filters['ip'] ?? null,
            $filters['hasil'] ?? null,
            $filters['app'] ?? null,
            $filters['q'] ?? null,
            $per_page,
            ($page -1) * max(1,$per_page),
            $filters['role'] ?? 'all',
            $filters['sort'] ?? 'desc'
        );

        $log_api = $this->Audit_model->get_log_api_paginated(
            $filters['start'] ?? null,
            $filters['end'] ?? null,
            $filters['q'] ?? null,
            $per_page,
            ($page -1) * max(1,$per_page),
            $filters['sort'] ?? 'desc'
        );

        $activity_html = $this->load->view('audit/partials/activity_rows', ['activity' => $activity, 'page' => $page, 'per_page' => $per_page], true);
        $api_html = $this->load->view('audit/partials/api_rows', ['log_api' => $log_api, 'page' => $page, 'per_page' => $per_page], true);

        echo json_encode(['activity_html' => $activity_html, 'api_html' => $api_html]);
    }

    // ======================================================
    // HALAMAN MANAGE APLIKASI & TOKEN
    // ======================================================
    public function apps()
    {
        $data['apps'] = $this->Audit_model->get_all_apps_with_tokens();
        $this->load->view('audit/apps', $data);
    }

    public function ajax_generate_token()
    {
        $id_aplikasi_raw = $this->input->post('id_aplikasi');

        if ($id_aplikasi_raw === null || $id_aplikasi_raw === '') {
            echo json_encode(['success' => false, 'message' => 'ID Aplikasi tidak valid']);
            return;
        }

        $id_aplikasi = (int) $id_aplikasi_raw;

        $app = $this->Api_model->getUserById($id_aplikasi);
        if (!$app) {
            echo json_encode(['success' => false, 'message' => 'Aplikasi tidak ditemukan']);
            return;
        }

        // Generate token baru
        $token = bin2hex(random_bytes(32));
        $data = [
            "id_aplikasi" => $id_aplikasi,
            "token"       => $token,
            "modidate"    => date("Y-m-d H:i:s"),
            "exp_date"    => date("Y-m-d H:i:s", strtotime("+2 minutes")),
            "use_date"    => null
        ];

        $this->Api_model->insertToken($data);

        echo json_encode([
            'success' => true, 
            'message' => 'Token berhasil dibuat',
            'token' => $token,
            'expired' => $data['exp_date']
        ]);
    }

    public function ajax_revoke_token()
    {
        $token = $this->input->post('token');
        
        if (!$token) {
            echo json_encode(['success' => false, 'message' => 'Token tidak valid']);
            return;
        }

        $this->db->where('token', $token);
        $this->db->delete('tb_token');

        echo json_encode(['success' => true, 'message' => 'Token berhasil dihapus']);
    }

    public function ajax_toggle_app_status()
    {
        $id_aplikasi_raw = $this->input->post('id_aplikasi');
        $status = $this->input->post('status');

        if ($id_aplikasi_raw === null || $id_aplikasi_raw === '') {
            echo json_encode(['success' => false, 'message' => 'ID Aplikasi tidak valid']);
            return;
        }

        $id_aplikasi = (int) $id_aplikasi_raw;

        $this->db->where('id_aplikasi', $id_aplikasi);
        $this->db->update('tb_user_app', ['status_active' => $status]);

        echo json_encode(['success' => true, 'message' => 'Status aplikasi berhasil diubah']);
    }

    public function ajax_bulk_toggle_app_status()
    {
        $ids = $this->input->post('ids');
        $status = (int) $this->input->post('status');

        if (!is_array($ids)) {
            $ids = $ids ? explode(',', (string) $ids) : [];
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), function ($id) {
            return $id >= 0;
        })));

        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada aplikasi yang dipilih']);
            return;
        }

        if (!in_array($status, [0, 1], true)) {
            echo json_encode(['success' => false, 'message' => 'Status tidak valid']);
            return;
        }

        $this->db->where_in('id_aplikasi', $ids);
        $this->db->update('tb_user_app', ['status_active' => $status]);

        $action = ($status === 1) ? 'diaktifkan' : 'dinonaktifkan';
        echo json_encode([
            'success' => true,
            'message' => count($ids) . ' aplikasi berhasil ' . $action
        ]);
    }

    public function ajax_bulk_delete_apps()
    {
        $ids = $this->input->post('ids');

        if (!is_array($ids)) {
            $ids = $ids ? explode(',', (string) $ids) : [];
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), function ($id) {
            return $id >= 0;
        })));

        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada aplikasi yang dipilih']);
            return;
        }

        $this->db->trans_start();

        $this->db->where_in('id_aplikasi', $ids);
        $this->db->delete('tb_token');

        $this->db->where_in('id_aplikasi', $ids);
        $this->db->delete('tb_user_app');

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus aplikasi terpilih']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => count($ids) . ' aplikasi berhasil dihapus'
        ]);
    }

    public function ajax_delete_unused_tokens()
    {
        $id_aplikasi_raw = $this->input->post('id_aplikasi');
        $has_app_filter = !($id_aplikasi_raw === null || $id_aplikasi_raw === '');

        $this->db->where('exp_date <', date('Y-m-d H:i:s'));
        if ($has_app_filter) {
            $this->db->where('id_aplikasi', (int) $id_aplikasi_raw);
        }

        $this->db->delete('tb_token');
        $deleted = $this->db->affected_rows();

        if ($deleted > 0) {
            echo json_encode([
                'success' => true,
                'message' => $deleted . ' token tidak terpakai berhasil dihapus'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Tidak ada token tidak terpakai untuk dihapus'
        ]);
    }

    public function download_client_library($language = '')
    {
        $language = strtolower(trim((string) $language));

        $libraries = [
            'php' => [
                'zip_name' => 'audit-trail-php-library.zip',
                'files' => [
                    'client-libraries/php/AuditTrailClient.php',
                    'client-libraries/php/example.php'
                ]
            ],
            'javascript' => [
                'zip_name' => 'audit-trail-javascript-library.zip',
                'files' => [
                    'client-libraries/javascript/AuditTrailClient.js',
                    'client-libraries/javascript/example.js'
                ]
            ],
            'python' => [
                'zip_name' => 'audit-trail-python-library.zip',
                'files' => [
                    'client-libraries/python/audit_trail_client.py',
                    'client-libraries/python/example.py'
                ]
            ]
        ];

        if (!isset($libraries[$language])) {
            show_404();
            return;
        }

        $this->load->library('zip');

        $added_files = 0;
        foreach ($libraries[$language]['files'] as $relative_path) {
            $full_path = FCPATH . $relative_path;
            if (is_file($full_path)) {
                $this->zip->read_file($full_path, basename($full_path));
                $added_files++;
            }
        }

        if ($added_files === 0) {
            show_error('File library tidak ditemukan.', 404);
            return;
        }

        $this->zip->download($libraries[$language]['zip_name']);
    }
}
