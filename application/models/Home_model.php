<?php
class Home_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function getAllTes()
    {
        $data = $this->db->query("SELECT name, date FROM tes")->result();
        return $data;
    }
}
