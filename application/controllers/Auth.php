<?php
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        // Don't load database - using MongoDB through User_model
        $this->load->model('User_model');
    }

    public function index()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }
        redirect('auth/login');
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $user = $this->User_model->login($email, $password);
                
                if ($user) {
                    $session_data = array(
                        'user_id' => $user['id'],
                        'user_name' => $user['name'],
                        'user_email' => $user['email'],
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Invalid email or password');
                }
            }
        }

        $this->load->view('frontend/template/header');
        $this->load->view('frontend/auth/login');
        $this->load->view('frontend/template/footer');
    }

    public function register()
    {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

            if ($this->form_validation->run()) {
                $data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
                );
                
                $user_id = $this->User_model->create_user($data);
                
                if ($user_id) {
                    $session_data = array(
                        'user_id' => $user_id,
                        'user_name' => $data['name'],
                        'user_email' => $data['email'],
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);
                    $this->session->set_flashdata('success', 'Registration successful! Welcome to Kundali Magic.');
                    redirect('dashboard');
                } else {
                    $this->session->set_flashdata('error', 'Registration failed. Please try again.');
                }
            }
        }

        $this->load->view('frontend/template/header');
        $this->load->view('frontend/auth/register');
        $this->load->view('frontend/template/footer');
    }

    public function logout()
    {
        $this->session->unset_userdata(array('user_id', 'user_name', 'user_email', 'logged_in'));
        $this->session->set_flashdata('success', 'You have been logged out successfully.');
        redirect('/');
    }

    // Custom validation callback for email uniqueness check with MongoDB
    public function email_check($email)
    {
        $user = $this->User_model->get_user_by_email($email);
        if ($user) {
            $this->form_validation->set_message('email_check', 'This email address is already registered.');
            return false;
        }
        return true;
    }
}
