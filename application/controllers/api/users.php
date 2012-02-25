<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Users extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',TRUE);
    }

// http://localhost/CodeIgniter_2.1.0_REST/index.php/api/users/put/format/json
    function put_put()
    {
        $data = array(
            // Example data - Replace with fields from the 'users' table.
                'name' => $this->put('name'),
                'email' => $this->put('email')
        );


        $id = $this->Users_model->save($data);


        $return = $this->Users_model->get_by_id($id)->row();


        if($return)
        {
            $this->response($return, 200);
        }
        else
        {
            $this->response(array('error' => 'Entity could not be created'), 404);
        }
    }

// http://localhost/CodeIgniter_2.1.0_REST/index.php/api/users/post/format/json
    function post_post()
    {
        $data = array(
            // Example data - Replace with fields from the 'users' table.
                'name' => $this->post('name'),
                'email' => $this->post('email')
        );


        $id = $this->Users_model->update($this->post('id'),$data);


        $return = $this->Users_model->get_by_id($this->post('id'))->row();


        if($return)
        {
            $this->response($return, 200);
        }
        else
        {
            $this->response(array('error' => 'Entity could not be created'), 404);
        }
    }

// http://localhost/CodeIgniter_2.1.0_REST/index.php/api/users/delete/id/53/format/json
    function delete_delete()
    {
        $data = $this->Users_model->delete($this->get('id'));


        $message = array(
            'data' => $data,
            'message' => 'DELETED!'
        );


        if($data)
        {
            $this->response($message, 200);
        }
        else
        {
            $this->response(array('error' => 'Entity could not be created'), 404);
        }
    }

// http://localhost/CodeIgniter_2.1.0_REST/index.php/api/users/get/id/1/format/json
    function get_get()
    {
        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }


        $data = $this->Users_model->get_by_id($this->get('id'))->row();


        if($data)
        {
            $this->response($data, 200);
        }
        else
        {
            $this->response(array('error' => 'Entity could not be found'), 404);
        }
    }

// http://localhost/CodeIgniter_2.1.0_REST/index.php/api/users/getsome/limit/5/offset/3/format/json
    function getsome_get()
    {
        $data = $this->Users_model->get_paged_list($this->get('limit'),$this->get('offset'))->result();


        if($data)
        {
            $this->response($data, 200);
        }
        else
        {
            $this->response(array('error' => 'Entity could not be found'), 404);
        }
    }

// http://localhost/CodeIgniter_2.1.0_REST/index.php/api/users/getall/format/json
    function getall_get()
    {
        $data = $this->Users_model->list_all()->result();


        if($data)
        {
            $this->response($data, 200);
        }
        else
        {
            $this->response(array('error' => 'Entity could not be found'), 404);
        }
    }


}