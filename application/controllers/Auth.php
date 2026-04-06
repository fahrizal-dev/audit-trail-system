<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('Admin_model');
        $this->load->model('Audit_model');
        $this->load->library('session');
    }

    // ================================
    // LOGIN PAGE
    // ================================
    public function login() {

        if ($this->session->userdata('admin_logged_in')) {
            redirect('audit');
            return;
        }

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Pragma: no-cache");

        $this->load->view('auth/login');
    }

    // ================================
    // LOGIN PROCESS
    // ================================
    public function login_process(){
        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');
        $ip       = $this->input->ip_address();

        if (!$username || !$password) {
            $this->session->set_flashdata('error', 'Username dan password wajib diisi.');
            redirect('auth/login');
        }

        $admin = $this->Admin_model->get_by_username($username);

        if(!$admin){
            $this->Audit_model->log([
                'id_aplikasi' => null,
                'username'    => $username,
                'menu'        => 'auth_admin',
                'aksi'        => 'LOGIN_FAIL',
                'status'      => 'NOT_FOUND',
                'keterangan'  => 'Login gagal: admin tidak ditemukan',
                'ip'          => $ip
            ]);

            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }

        $stored = $admin->password;
        $ok = false;
        if (is_string($stored) && strlen($stored) === 64 && ctype_xdigit($stored)) {
            if (hash('sha256', $password) === $stored) $ok = true;
        } else {
            if (password_verify($password, $stored)) $ok = true;
        }

        if (!$ok) {
            $this->Audit_model->log([
                'id_aplikasi' => null,
                'username'    => $admin->username,
                'menu'        => 'auth_admin',
                'aksi'        => 'LOGIN_FAIL',
                'status'      => 'WRONG_PASSWORD',
                'keterangan'  => 'Login gagal: password salah',
                'ip'          => $ip
            ]);

            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth/login');
        }

        $this->session->set_userdata([
            'admin_logged_in' => TRUE,
            'admin_username'  => $admin->username,
            'admin_nama'      => $admin->nama,
            'admin_jabatan'   => $admin->jabatan
        ]);

        $this->Audit_model->log([
            'id_aplikasi' => null,
            'username'    => $admin->username,
            'menu'        => 'auth_admin',
            'aksi'        => 'LOGIN',
            'status'      => 'SUCCESS',
            'keterangan'  => 'Admin berhasil login',
            'ip'          => $ip
        ]);

        $this->session->set_flashdata('success', 'Berhasil login!');
        redirect('audit');
    }

    // ================================
    // REGISTER PAGE (DIBENARKAN)
    // ================================
    public function register(){

        if ($this->session->userdata('admin_logged_in')) {
            redirect('audit');
            return;
        }

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Pragma: no-cache");

        $this->load->view('auth/register');
    }

    // ================================
    // REGISTER PROCESS
    // ================================
    public function register_process()
    {
        $username = trim($this->input->post('username'));
        $email    = trim($this->input->post('email'));
        $nama     = $this->input->post('nama');
        $jabatan  = $this->input->post('jabatan');
        $password = $this->input->post('password');
        $confirm  = $this->input->post('confirm_password');

        $cek_username = $this->db->get_where('tb_admin', ['username' => $username])->row();
        if ($cek_username) {
            $this->session->set_flashdata('error', 'Username sudah digunakan, silakan pilih yang lain.');
            redirect('auth/register');
            return;
        }

        $cek_email = $this->db->get_where('tb_admin', ['email' => $email])->row();
        if ($cek_email) {
            $this->session->set_flashdata('error', 'Email sudah terdaftar, gunakan email lain.');
            redirect('auth/register');
            return;
        }

        if ($password !== $confirm) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok!');
            redirect('auth/register');
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'username'      => $username,
            'email'         => $email,
            'nama'          => $nama,
            'jabatan'       => $jabatan,
            'password'      => $hash,
            'status_active' => 1,
            'modiby'        => 'SYSTEM',
            'modidate'      => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tb_admin', $data);

        $this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');
        redirect('auth/login');
    }

    // ================================
    // LOGOUT (AMAN)
    // ================================
    public function logout(){
        $username = $this->session->userdata('admin_username');
        $ip       = $this->input->ip_address();

        if ($username) {
            $this->Audit_model->log([
                'id_aplikasi' => null,
                'username'    => $username,
                'menu'        => 'auth_admin',
                'aksi'        => 'LOGOUT',
                'status'      => 'SUCCESS',
                'keterangan'  => 'Admin berhasil logout',
                'ip'          => $ip
            ]);
        }

        $this->session->set_flashdata('success', 'Berhasil logout!');

        $this->session->unset_userdata([
            'admin_logged_in',
            'admin_username',
            'admin_nama',
            'admin_jabatan'
        ]);

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Pragma: no-cache");

        redirect('auth/login');
    }
}