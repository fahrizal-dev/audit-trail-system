<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_auth_model extends CI_Model
{
    public function get_app_by_username($username)
    {
        return $this->db
            ->where('user_name', $username)
            ->get('tb_user_app')
            ->row();
    }
}
