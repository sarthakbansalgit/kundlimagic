<?php
    class Error404 extends CI_Controller{
        public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

        public function index(){
            
            $this->output->set_status_header('404');
            $this->load->view('frontend/template/header');
            $this->load->view('frontend/template/navbar');
            $this->load->view('frontend/error404');
            $this->load->view('frontend/template/footer');
            
        }

    }

?>  