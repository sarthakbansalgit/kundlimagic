<?php
class Downloadpdf extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('admin/Plansmodel');
    }
    public function index()
    {
        $this->load->view('frontend/template/header');
        $this->load->view('frontend/downloadpdf');
        $this->load->view('frontend/template/footer');
    }
}
