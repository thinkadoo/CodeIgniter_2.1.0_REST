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

/*    function add()
    {
        // set empty default form field values
        $this->_set_fields();
        // set validation properties
        $this->_set_rules();

        // set common properties
        $data['title'] = 'Add new person';
        $data['message'] = '';
        $data['action'] = site_url('person/addPerson');
        $data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));

        // load view
        $this->load->view('personEdit', $data);
    }

    function addPerson()
    {
        // set common properties
        $data['title'] = 'Add new person';
        $data['action'] = site_url('person/addPerson');
        $data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));

        // set empty default form field values
        $this->_set_fields();
        // set validation properties
        $this->_set_rules();

        // run validation
        if ($this->form_validation->run() == FALSE)
        {
            $data['message'] = '';
        }
        else
        {
            // save data
            $person = array('name' => $this->input->post('name'),
                'gender' => $this->input->post('gender'),
                'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
            $id = $this->Person_model->save($person);

            // set user message
            $data['message'] = '<div class="success">add new person success</div>';
        }

        // load view
        $this->load->view('personEdit', $data);
    }*/


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

/*    function update($id)
    {
        // set validation properties
        $this->_set_rules();

        // prefill form values
        $person = $this->Person_model->get_by_id($id)->row();
        $this->form_data->id = $id;
        $this->form_data->name = $person->name;
        $this->form_data->gender = strtoupper($person->gender);
        $this->form_data->dob = date('d-m-Y',strtotime($person->dob));

        // set common properties
        $data['title'] = 'Update person';
        $data['message'] = '';
        $data['action'] = site_url('person/updatePerson');
        $data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));

        // load view
        $this->load->view('personEdit', $data);
    }

    function updatePerson()
    {
        // set common properties
        $data['title'] = 'Update person';
        $data['action'] = site_url('person/updatePerson');
        $data['link_back'] = anchor('person/index/','Back to list of persons',array('class'=>'back'));

        // set empty default form field values
        $this->_set_fields();
        // set validation properties
        $this->_set_rules();

        // run validation
        if ($this->form_validation->run() == FALSE)
        {
            $data['message'] = '';
        }
        else
        {
            // save data
            $id = $this->input->post('id');
            $person = array('name' => $this->input->post('name'),
                'gender' => $this->input->post('gender'),
                'dob' => date('Y-m-d', strtotime($this->input->post('dob'))));
            $this->Person_model->update($id,$person);

            // set user message
            $data['message'] = '<div class="success">update person success</div>';
        }

        // load view
        $this->load->view('personEdit', $data);
    }

    function delete($id)
    {
        // delete person
        $this->Person_model->delete($id);

        // redirect to person list page
        redirect('person/index/','refresh');
    }

    // set empty default form field values
    function _set_fields()
    {
        $this->form_data->id = '';
        $this->form_data->name = '';
        $this->form_data->gender = '';
        $this->form_data->dob = '';
    }

    // validation rules
    function _set_rules()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
        $this->form_validation->set_rules('dob', 'DoB', 'trim|required|callback_valid_date');

        $this->form_validation->set_message('required', '* required');
        $this->form_validation->set_message('isset', '* required');
        $this->form_validation->set_message('valid_date', 'date format is not valid. dd-mm-yyyy');
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
    }

    // date_validation callback
    function valid_date($str)
    {
        //match the format of the date
        if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $str, $parts))
        {
            //check weather the date is valid of not
            if(checkdate($parts[2],$parts[1],$parts[3]))
                return true;
            else
                return false;
        }
        else
            return false;
    }*/
}