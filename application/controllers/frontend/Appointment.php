<?php
class Appointment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('User_model');
    }
    
    public function index()
    {
        if ($this->input->post()) {
            // If user is logged in, save their kundli
            if ($this->session->userdata('logged_in')) {
                $this->save_user_kundli();
            }
        }

        $this->load->view('frontend/template/header');
        $this->load->view('frontend/template/navbar');
        $this->load->view('frontend/appointment');
        $this->load->view('frontend/template/footer');
    }
    
    private function save_user_kundli()
    {
        $kundli_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'name' => $this->input->post('name'),
            'birth_date' => $this->input->post('dob'),
            'birth_time' => $this->input->post('tob'),
            'birth_place' => $this->input->post('pob'),
            'kundli_data' => json_encode(array(
                'gender' => $this->input->post('gender'),
                'language' => $this->input->post('language'),
                'kundli_type' => $this->input->post('kundli_type'),
                'lat' => $this->input->post('lat'),
                'long' => $this->input->post('long')
            ))
        );
        
        $this->User_model->save_kundli($kundli_data);
    }
}
