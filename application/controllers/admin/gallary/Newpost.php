<?php
class Newpost extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('vendorAuth')) {
            redirect('login');
        }
        
        $this->load->model('admin/gallary/Newpostmodel');
    }

    public function index()
    {
        $data['fetch_cate'] = $this->Newpostmodel->fetch_cate();
        $this->load->view('admin/template/header');
        $this->load->view('admin/template/sidebar');
        $this->load->view('admin/template/topbar');
        $this->load->view('admin/gallary/newpost',$data);
        $this->load->view('admin/template/footer');
    }


    public function upload()
    {
        $this->load->model('admin/gallary/Newpostmodel');
        $this->input->post('formSubmit');
        
        $img_data = array();
        // Count total files
        $countfiles = count($_FILES['files']['name']);
        
        
        // Looping all files
        for ($i = 0; $i < $countfiles; $i++) {

            if (!empty($_FILES['files']['name'][$i])) {
                
                // Define new $_FILES array - $_FILES['file']
                $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                // Set preference
                $config['upload_path'] = APPPATH . '../upload/gallary';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = '100000'; // max_size in kb
                $config['file_name'] = $_FILES['files']['name'][$i];
                
                //Load upload library
                $this->load->library('upload', $config);

                // File upload
                if ($this->upload->do_upload('file')) {
                    // Get data about the file
                    $uploadData = $this->upload->data();
                    $filename = $uploadData['file_name'];

                    
                    // Initialize array
                    $img_data[] = $filename;
                }
            }
        }
        
        for($j=0; $j<$countfiles; $j++){



            $datas = array(
                'image' => $img_data[$j],
                'cate' => $this->input->post('cate'),
                
            );
            
            $fina = $this->Newpostmodel->upload_gallary($datas);

        }
        



     
        if ($fina == true) {
            $this->session->set_flashdata('success', 'Gallary Uploaded');
            redirect(base_url() . 'admin/gallary/newpost');
        } else {
            $this->session->set_flashdata('error', 'Error In Updating Please Try Again');
            redirect(base_url() . 'admin/gallary/newpost');
        }
    }


    
}
