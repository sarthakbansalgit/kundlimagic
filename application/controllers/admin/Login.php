<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Vendorloginmodel');
        $this->load->library('form_validation');
    }
    public function index()
    {
        $this->load->helper(array('cookie', 'url'));
        if ($this->session->userdata('vendorAuth') != "") {

            redirect(base_url() . "welcome");
        }
        // login data
        if ($this->input->post('vendorLogin')) {
            $this->form_validation->set_rules('email', 'text', 'required|valid_email');
            $this->form_validation->set_rules('password', 'text', 'required');
            if ($this->form_validation->run() == true) {
                $setArrayData = array('email' => $this->input->post('email'), 'pass' => $this->input->post('password'));
                set_cookie('email', $setArrayData[0]);
                set_cookie('pass', $setArrayData[1]);

                $getVendorLoginStatus = $this->Vendorloginmodel->loginVendor($setArrayData);
                if ($getVendorLoginStatus['message'] == 'email') {
                    $this->session->set_flashdata('error', 'Please enter correct email address');
                    $this->load->helper('url');
                    redirect(base_url() . "admin/login", 'refresh');
                } else if ($getVendorLoginStatus['message'] == 'pass') {
                    $this->session->set_flashdata('error', 'Please enter correct password');
                    $this->load->helper('url');
                    redirect(base_url() . "admin/login", 'refresh');
                } else if ($getVendorLoginStatus['message'] == 'yes') {
                    $this->session->set_userdata('vendorAuth', $getVendorLoginStatus['token']);
                    $this->session->set_userdata('vendorRole', $getVendorLoginStatus['role']);
                    $this->load->helper('url');
                    redirect(base_url() . "welcome");
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong. Please try again');
                    $this->load->helper('url');
                    redirect(base_url() . "admin/login", 'refresh');
                }
            } else {
                $this->session->set_flashdata('error', 'All fields are mandatory');
                $this->load->helper('url');
                redirect(base_url() . "admin/login", 'refresh');
            }
        }
        $this->load->view('login');
    }
    public function setLoginAuth()
    {
        if ($this->session->userdata('vendorAuth') != "") {
            $this->load->helper('url');
            redirect(base_url() . "dashboard", 'refresh');
        }
        if ($_COOKIE['tempAuth'] != "") {
            $getAuthStatus = $this->Vendorloginmodel->checkLandinUserAuth($_COOKIE['tempAuth']);
            if ($getAuthStatus['message'] == 'wrong') {
                unset($_COOKIE['tempAuth']);
                $this->session->set_flashdata('error', 'Unauthorized access');
                $this->load->helper('url');
                redirect(base_url(), 'refresh');
            } else if ($getAuthStatus['message'] == 'yes') {
                unset($_COOKIE['tempAuth']);
                $this->session->set_userdata('vendorAuth', $getAuthStatus['vendorAuth']);
                $this->load->helper('url');
                redirect(base_url() . "Dashboard", 'refresh');
            } else {
                unset($_COOKIE['tempAuth']);
                $this->session->set_flashdata('error', 'Something went wrong. Please try again');
                $this->load->helper('url');
                redirect(base_url(), 'refresh');
            }
        } else {
            $this->load->helper('url');
            redirect(base_url(), 'refresh');
        }
    }
    public function logoutVendor()
    {
        $getauthData = $this->session->userdata('vendorAuth');
        $getauthDataRole = $this->session->userdata('vendorRole');
        if ($getauthData != "") {
            $this->Vendorloginmodel->logoutVendorData($getauthData, $getauthDataRole);
            $this->session->unset_userdata('vendorAuth');
            $this->session->unset_userdata('vendorRole');
            $this->session->set_flashdata('success', 'You are logout successfully');
            $this->load->helper('url');
            redirect(base_url() . "admin/login", 'refresh');
        } else {
            $this->session->set_flashdata('success', 'You are logout successfully');
            $this->load->helper('url');
            redirect(base_url() . "admin/login", 'refresh');
        }
    }
    // forget Password
    public function forgetCheckData()
    {
        $this->form_validation->set_rules('checkEmail', 'text', 'required|valid_email');
        if ($this->form_validation->run() == true) {
            $getForgetToken = $this->Vendorloginmodel->checkForgetEmail($this->input->post('checkEmail'));
            if ($getForgetToken['message'] == 'yes') {
                // send email to admin user

                $htm = '<table class="body-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                <td class="container"  style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; width:100%; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                <div class="content" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td class="alert alert-warning" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 16px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #9e9e9e4a;    border-bottom: 3px solid #000; margin: 0; padding: 20px;" align="center"  valign="top">
                <img src="' . base_url() . 'assets/images/logo_kci.png" style="max-width: 20%;width:100%"/></td></tr>
                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td class="content-wrap" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
                <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><strong style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">Your Security Pin is:</strong></td></tr>
                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">' . $getForgetToken['token'] . '</td></tr>
                <tr style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                    <td class="content-block" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top">Thanks for choosing Kelam Career Institute.</td></tr></table></td></tr></table>
                <div  style="text-align: center; font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box;background: #000; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 10px 0px;">
                <table width="100%" ><tr >
                <td  ><a href="#" style="  font-size: 12px; color: #fff; text-decoration: none; margin: 0;">&copy;Kelam Career Institute 2018--All right reserverd </td></tr>
                </table>
                </div>
                </div></td></tr></table>';



                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->to($this->input->post('checkEmail'));
                $this->email->from('info@kelam.co.in', 'kelam career institute');
                $this->email->subject('Forgot Password');
                $this->email->message($htm);




                if ($this->email->send()) {
                    $this->session->set_flashdata('success', 'Please check your email');
                    $this->session->set_userdata('tempCode', $getForgetToken['token']);
                    echo 'yes';
                } else {
                    echo 'no';
                }
            } else if ($getForgetToken['message'] == 'email') {
                echo 'email';
            } else {
                echo 'no';
            }
        } else {
            echo 'all';
        }
    }

    function sendMail()
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'mail.kelam.co.in',
            'smtp_port' => 465,
            'smtp_user' => 'ssl://info@kelam.co.in', // change it to yours
            'smtp_pass' => 'zL:Hyq28H0-6Da', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );

        $message = '';
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('info@kelam.co.in'); // change it to yours
        $this->email->to('deepsharma906@gmail.com'); // change it to yours
        $this->email->subject('Resume from JobsBuddy for your Job posting');
        $this->email->message($message);
        if ($this->email->send()) {
            echo 'Email sent.';
        } else {
            show_error($this->email->print_debugger());
        }
    }
}
