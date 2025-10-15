<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Profile extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('login');
        }
        $this->load->helper('url');
        $this->perPage = 100;
        $this->load->model('admin/Profilemodel');
        $this->auth_users->is_logged_in();
    }


    public function index()
    {
        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $profileData['profile'] = $this->Profilemodel->fetchprofile();
        $this->load->view('admin/profile', $profileData);
        $this->load->view('admin/template/footer');
    }


    function updatepass()
    {


        $getOrginalPassword = $this->input->post('password');
        $getRePassword = $this->input->post('confirmpassword');
        if ($getOrginalPassword == $getRePassword) {
            $setArrayData = array('passwordData' => $getOrginalPassword);
            $getUpdatePassStatus = $this->Profilemodel->updateUserPass($setArrayData);
            if ($getUpdatePassStatus['message'] == 'yes') {
                $this->session->unset_userdata('tempCode');
                $this->session->set_flashdata('success', 'Your password updated successfully');

                redirect(base_url() . 'admin/profile');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again');

                redirect(base_url() . 'admin/profile');
            }
        } else {
            $this->session->set_flashdata('error', 'Passwords are not matched');
            redirect(base_url() . 'admin/profile');
        }
    }




    public function slidereditsve()
    {


        if ($this->input->post('formSubmit')) {
            $title =  $this->input->post('name');
            $short_desc = $this->input->post('cdesc');

            if (!empty($_FILES['images']['name'])) {

                $File_name = 'image-' . strtotime(date('YmdHis'));

                $config['upload_path'] = APPPATH . '../upload/profilepic';
                $config['file_name'] = $File_name;
                $config['overwrite'] = TRUE;
                $config["allowed_types"] = 'jpg|jpeg|png';
                $config["max_size"] = 1024;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('images')) {

                    $this->data['error'] = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    //  $this->session->set_flashdata('error','Something went wrong when saving the file, please try again.');
                    redirect('admin/profile');
                } else {
                    $dataimage_return = $this->upload->data();
                    $imageurl = base_url() . 'upload/profilepic/' . $dataimage_return['file_name'];
                    $insert_data = array('image' => $imageurl, 'update_date' => strtotime(date('YmdHis')));
                    $file_id = $this->Profilemodel->updte_profile_img($insert_data);
                }
            }

            $insert_data = array('staff_name' => $title, 'staff_email' => $short_desc, 'update_date' => strtotime(date('YmdHis')));

            $file_id = $this->Profilemodel->updte_profile($insert_data);
            if ($file_id) {
                $this->session->set_flashdata('success', 'Profile Successfully Updte');
                redirect('admin/profile');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong when saving the file, please try again.');
                redirect('admin/profile');
            }
        } else {
            $this->session->set_flashdata('error', 'Something went wrong when saving the file, please try again.');
            redirect('admin/profile');
        }
    }
}
