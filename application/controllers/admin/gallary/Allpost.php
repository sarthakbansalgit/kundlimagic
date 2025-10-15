<?php
  class Allpost extends CI_Controller{

        public function __construct()
        {
            parent::__construct();
            if(! $this->session->userdata('vendorAuth')){
            redirect('admin/login');}
            $this->load->model('admin/gallary/Newpostmodel');
        }
              
        public function index(){
            $this->load->view('admin/template/header');
            $this->load->view('admin/template/sidebar');
            $this->load->view('admin/template/topbar');
            $this->load->view('admin/gallary/allpost');
            $this->load->view('admin/template/footer');
        }
        
       
     
       
       
       public function addinventory_api(){

            $getPurchaseData = $this->Newpostmodel->fetch_gallary();

            foreach ($getPurchaseData as $key => $value) { 
           
                $arrya_json[] = array($value['id'],'<img src="'.base_url().'upload/gallary/'.$value['image'].'">',$value['cate'],
               '<a class="delete_sliders" data-id="'.$value['id'].'"  style="color: red;cursor: pointer;" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>' );
                }
                 echo json_encode(array('data'=>$arrya_json));
            }
      
    public function deletepost(){ 
    
                    if($this->input->post('deletesliderId'))
                {
                  $this->form_validation->set_rules('deletesliderId','text','required');
                  if($this->form_validation->run() == true)
                  {
                    $getDeleteStatus = $this->Newpostmodel->delete_gallary($this->input->post('deletesliderId'));
                    if($getDeleteStatus['message'] == 'yes')
                    {
                      $this->session->set_flashdata('success','gallary  deleted successfully');
                      redirect(base_url()."admin/gallary/allpost");
                    }
                    else
                    {
                      $this->session->set_flashdata('error','Something went wrong. Please try again');
                    redirect(base_url()."admin/gallary/allpost");
                      
                    }
                  }
                  else
                  {
                    $this->session->set_flashdata('error','Something went wrong. Please try again');
                    redirect(base_url()."admin/gallary/allpost");
    
                  }
                }
              }

  
    }

?>