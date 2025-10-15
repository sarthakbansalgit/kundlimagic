<?php
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->User_model->get_user($user_id);
        
        if (!$data['user']) {
            // User not found, clear session and redirect to login
            $this->session->unset_userdata(array('user_id', 'user_name', 'user_email', 'logged_in'));
            redirect('auth/login');
        }
        
        $data['kundlis'] = $this->User_model->get_user_kundlis($user_id);
        
        $this->load->view('frontend/template/header');
        $this->load->view('frontend/template/navbar');
        $this->load->view('frontend/dashboard/index', $data);
        $this->load->view('frontend/template/footer');
    }

    public function profile()
    {
        $data['user'] = $this->User_model->get_user($this->session->userdata('user_id'));
        
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'required|min_length[2]');
            $this->form_validation->set_rules('phone', 'Phone Number', 'required|numeric|min_length[10]');
            $this->form_validation->set_rules('pob', 'Place of Birth', 'required');
            
            if ($this->form_validation->run()) {
                $update_data = array(
                    'name' => $this->input->post('name'),
                    'phone' => $this->input->post('phone'),
                    'pob' => $this->input->post('pob')
                );
                
                if ($this->User_model->update_user($this->session->userdata('user_id'), $update_data)) {
                    $this->session->set_userdata('user_name', $update_data['name']);
                    $this->session->set_flashdata('success', 'Profile updated successfully!');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
                }
                redirect('dashboard/profile');
            }
        }
        
        $this->load->view('frontend/template/header');
        $this->load->view('frontend/template/navbar');
        $this->load->view('frontend/dashboard/profile', $data);
        $this->load->view('frontend/template/footer');
    }

    public function view_kundli($kundli_id)
    {
        $data['kundli'] = $this->User_model->get_kundli($kundli_id, $this->session->userdata('user_id'));
        
        if (!$data['kundli']) {
            show_404();
        }
        
        $this->load->view('frontend/template/header');
        $this->load->view('frontend/template/navbar');
        $this->load->view('frontend/dashboard/view_kundli', $data);
        $this->load->view('frontend/template/footer');
    }
    
    public function download_pdf($kundli_id)
    {
        $kundli = $this->User_model->get_kundli($kundli_id, $this->session->userdata('user_id'));
        
        if (!$kundli) {
            show_404();
            return;
        }
        
        // Check if local PDF exists
        if ($kundli->local_pdf_path && file_exists(FCPATH . $kundli->local_pdf_path)) {
            $file_path = FCPATH . $kundli->local_pdf_path;
            $filename = basename($file_path);
            
            // Set headers for PDF download
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($file_path));
            
            // Output the file
            readfile($file_path);
            exit;
        } else {
            // Fallback to external URL if local file doesn't exist
            $kundli_data = json_decode($kundli->kundli_data, true);
            if (isset($kundli_data['pdf_url'])) {
                redirect($kundli_data['pdf_url']);
            } else {
                show_404();
            }
        }
    }
}
