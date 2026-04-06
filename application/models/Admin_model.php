<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        if (!isset($this->db)) $this->load->database();
    }

    public function get_by_username($username){
        return $this->db->get_where('tb_admin', ['username' => $username])->row();
    }

    public function username_exists($username){
        return $this->db->get_where('tb_admin', ['username' => $username])->num_rows() > 0;
    }

    public function insert($data){
        return $this->db->insert('tb_admin', $data);
    }

    public function update($username, $data){
        $this->db->where('username', $username);
        return $this->db->update('tb_admin', $data);
    }
}
