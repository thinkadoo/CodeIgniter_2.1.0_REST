<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Person_model extends CI_Model {

    private $tbl_person = 'tbl_person';

    public function __construct()
    {
        parent::__construct();
    }


    public function list_all()
    {
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_person);
    }


    public function count_all()
    {
        return $this->db->count_all($this->tbl_person);
    }


    public function get_paged_list($limit = 10, $offset = 0)
    {
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl_person, $limit, $offset);
    }


    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_person);
    }


    public function save($tbl_person)
    {
        $this->db->insert($this->tbl_person, $tbl_person);
        return $this->db->insert_id();
    }


    public function update($id, $tbl_person)
    {
        $this->db->where('id', $id);
        $this->db->update($this->tbl_person, $tbl_person);
        return $id;
    }


    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->tbl_person);
        return $id;
    }


}