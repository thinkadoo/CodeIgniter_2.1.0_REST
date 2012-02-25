<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Crud extends REST_Controller
{
    // num of records per page
    private $limit = 10;

    function __construct()
    {
        parent::__construct();

        // load model
        $this->load->model('Person_model','',TRUE);
        $this->load->model('Users_model','',TRUE);
    }

/*    function add_get()
    {
            // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/add/name/BooooB/email/boobgmail.com
            $person = array(
                'name' => $this->get('name'),
                'email' => $this->get('email')
            );
            $id = $this->Users_model->save($person);


        $person = $this->Users_model->get_by_id($id)->row();

        if($person)
        {
            $this->response($person, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }

    }*/

    function user_put()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/user/format/json
        $person = array(
            'name' => $this->put('name'),
            'email' => $this->put('email')
        );
        $id = $this->Users_model->save($person);


        $person = $this->Users_model->get_by_id($id)->row();

        if($person)
        {
            $this->response($person, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }

    }

/*    function save_get()
    {
            // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/save/id/54/name/SSSSSSSSSSSSSSSSSS/email/boobgmail.com
            $person = array(
                'name' => $this->get('name'),
                'email' => $this->get('email')
            );
            $id = $this->Users_model->update($this->get('id'),$person);


        $person = $this->Users_model->get_by_id($this->get('id'))->row();

        if($person)
        {
            $this->response($person, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }

    }*/

    function user_post()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/user/format/json
        $person = array(
            'name' => $this->post('name'),
            'email' => $this->post('email')
        );

        $id = $this->Users_model->update($this->post('id'),$person);

        $person = $this->Users_model->get_by_id($this->post('id'))->row();

        if($person)
        {
            $this->response($person, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }

    }

    /*    function delete_get()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/delete/id/49/format/json

        $users = $this->Users_model->delete($this->get('id'));
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');

        $this->response($message, 200); // 200 being the HTTP response code

    }*/

    function user_delete()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/user/id/52/format/json

        $user = $this->Users_model->delete($this->get('id'));

        $message = array
        (
            'user' => $user,
            'message' => 'DELETED!'
        );

        $this->response($message, 200); // 200 being the HTTP response code
    }

    function user_get()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/user/id/1/format/json

        if(!$this->get('id'))
        {
            $this->response(NULL, 400);
        }

        $user = $this->Users_model->get_by_id($this->get('id'))->row();

        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

    function users_get()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/users/limit/20/offset/5/format/json

        $users = $this->Users_model->get_paged_list($this->get('limit'),$this->get('offset'))->result();

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }


    function allusers_get()
    {
        // http://localhost/CodeIgniter_2.1.0_REST/index.php/api/crud/allusers/format/json

        $users = $this->Users_model->list_all()->result();

        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }

}