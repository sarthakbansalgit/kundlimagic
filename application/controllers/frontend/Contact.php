<?php
class Contact extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend/Contactmodel');
    }
    public function index()
    {

        $this->load->view('frontend/template/header');
        $this->load->view('frontend/template/navbar');
        $this->load->view('frontend/contact');
        $this->load->view('frontend/template/footer');
    }

    public function insert_data()
    {
        $this->load->model('frontend/Contactmodel');
        $this->input->post('formSubmit');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('number', 'number', 'required');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('msg', 'Message', 'required');
        if ($this->form_validation->run()) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $number = $this->input->post('number');
            $sub = $this->input->post('subject');
            $msg = $this->input->post('msg');
            if ($this->Contactmodel->insert_data($name, $email, $number,$sub, $msg)) {

                echo json_encode([
                    "status" => "Success",
                    "message" => "Thank you for showing interest.  Our Team will Call/Connect/Reply you ASAP."
                ]);
                
            } else {

                echo json_encode([
                    "status" => "Error",
                    "message" => "Error In Submission!"
                ]);
            }
        } else {

            echo json_encode([
                "status" => "Error",
                "message" => validation_errors()
            ]);
        }
    }


    public function insert_subscriber()
{
    $this->load->model('frontend/Contactmodel');

    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    if ($this->form_validation->run()) {
        $email = $this->input->post('email');

        if ($this->Contactmodel->insert_subscriber($email)) {
            echo json_encode([
                "status" => "Success",
                "message" => "Thanks For Subscribing!"
            ]);
        } else {
            echo json_encode([
                "status" => "Error",
                "message" => "Error In Submission!"
            ]);
        }
    } else {
        echo json_encode([
            "status" => "Error",
            "message" => validation_errors()
        ]);
    }

    exit(); // important to stop further output
}

}
