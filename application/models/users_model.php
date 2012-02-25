<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    private $users = 'users';

    public function __construct()
    {
        parent::__construct();
    }


    public function list_all()
    {
        $this->db->order_by('id','asc');
        return $this->db->get($this->users);
    }


    public function count_all()
    {
        return $this->db->count_all($this->users);
    }


    public function get_paged_list($limit = 10, $offset = 0)
    {
        $this->db->order_by('id','asc');
        return $this->db->get($this->users, $limit, $offset);
    }


    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->users);
    }


    public function save($users)
    {
        $this->db->insert($this->users, $users);
        return $this->db->insert_id();
    }


    public function update($id, $users)
    {
        $this->db->where('id', $id);
        $this->db->update($this->users, $users);
        return $id;
    }


    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->users);
        return $id;
    }


}