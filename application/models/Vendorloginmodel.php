<?php
class Vendorloginmodel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    public function checkLandinUserAuth($data)
    {
        $masterDB = $this->load->database('master', TRUE);
        $masterDB->select('houdin_vendor_auth_id,houdin_vendor_auth_vendor_id')->from('houdin_vendor_auth')->where('houdin_vendor_auth_token',$data);
        $getAuthToken = $masterDB->get()->result();
        if(count($getAuthToken) > 0)
        {
            $letters1='abcdefghijklmnopqrstuvwxyz';
            $string1='';
            for($x=0; $x<3; ++$x)
            {
                $string1.=$letters1[rand(0,25)].rand(0,9);
            }
            $updateUserAuthData = array('houdin_vendor_auth_token'=>$string1,'houdin_vendor_auth_url_token'=>$string1);
            $masterDB->where('houdin_vendor_auth_id',$getAuthToken[0]->houdin_vendor_auth_id);
            $getUpdateStaus = $masterDB->update('houdin_vendor_auth',$updateUserAuthData);
            if($getUpdateStaus == 1)
            {
                // insert houdin user log
                $ip=$_SERVER['REMOTE_ADDR'];
                $browser =  $this->get_browser_name($_SERVER['HTTP_USER_AGENT']);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $ip_data_in = curl_exec($ch); // string
                curl_close($ch);

                $ip_data = json_decode($ip_data_in,true);
                $ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

                if($ip_data && $ip_data['geoplugin_countryName'] != null) {
                   $country = $ip_data['geoplugin_countryName'];
                }
                else
                {
                    $country='';
                }
                $setData = strtotime(date('Y-m-d h:i:s'));
                $setInsertArray = array('houdin_vendor_user_log_userid'=>$getAuthToken[0]->houdin_vendor_auth_vendor_id,'houdin_vendor_user_log_ip_address'=>$ip,'houdin_vendor_user_log_browser'=>$browser,'houdin_vendor_user_log_location'=>$country,'houdin_vendor_user_log_created_at'=>$setData);
                $getInsertStatus = $masterDB->insert('houdin_vendor_user_log',$setInsertArray);
                return array('message'=>'yes','vendorAuth'=>$string1);
            }
            else
            {
                return array('message'=>'no');
            }
        }
        else
        {
            return array('message'=>'wrong');
        }
    }
    public  function get_browser_name($user_agent)
    {
        if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
        elseif (strpos($user_agent, 'Edge')) return 'Edge';
        elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
        elseif (strpos($user_agent, 'Safari')) return 'Safari';
        elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
        elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
        return 'Other';
    }
    public function logoutVendorData($data,$getauthDataRole)
    {

        if($getauthDataRole && $getauthDataRole==1)
        {
     $updateArray = array('houdinv_staff_auth_token'=>'','houdinv_staff_auth_url_token'=>'');
                $this->db->where("houdinv_staff_auth_token",$data);
                $getStaffInsertStatus = $this->db->update('houdinv_staff_management',$updateArray);

        }
        else
        {
        $masterDB = $this->load->database('master', TRUE);
        $masterDB->where('houdin_vendor_auth_token',$data);
        $masterDB->delete('houdin_vendor_auth');
        }
    }
    public function loginVendor($data)
    {

        $staff_data = $this->db->select('*')->from('houdinv_staff_management')
                ->where('staff_email',$data['email'])
                ->get()->result();


                // new
           if(count($staff_data) > 0)
                {
                    $Staff_getUserIdData = $staff_data[0]->staff_id;

                    $Staff_hash_input_user_pass= crypt($data['pass'],$staff_data[0]->staff_password_salt);


                    $this->db->select('*')->from('houdinv_staff_management')->where('staff_password',$Staff_hash_input_user_pass);
                    $Staff_getPasswordData = $this->db->get()->result();

                    $random1='';

                    if(count($Staff_getPasswordData) > 0)
                    {
                        $data1 = "AbcDE123IJKLMN67QRSTUVWXYZ";
                        $data1 .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
                        $data1 .= "0FGH45OP89";
                        for($i = 0; $i < 6; $i++)
                        {
                        $random1 .= substr($data1, (rand()%(strlen($data1))), 1);
                        }
                        $updateArray = array('houdinv_staff_auth_token'=>$random1,'houdinv_staff_auth_url_token'=>$random1);
                        $this->db->where("staff_id",$Staff_getPasswordData[0]->staff_id);
                        $getStaffInsertStatus = $this->db->update('houdinv_staff_management',$updateArray);
                        if($getStaffInsertStatus == 1)
                        {
                            return array('message'=>'yes','token'=>$random1,'role'=>1);
                        }
                        else
                        {
                            return array('message'=>'no');
                        }
                    }
                    else
                    {
                        return array('message'=>'pass');
                    }
                }
                else
                {
                     return array('message'=>'email');
                }


        }

    public function checkForgetEmail($data)
    {

        $this->db->select('staff_id')->from('houdinv_staff_management')->where('staff_email',$data);
        $getEmailIdData = $this->db->get()->result();
        if(count($getEmailIdData) > 0)
        {
            $getUserId = $getEmailIdData[0]->staff_id;
            $token = rand(99999,10000);
            $setInsertArray = array('houdin_user_forgot_password_user_id'=>$getUserId,'houdin_user_forgot_password_token'=>$token);
            $getInsertStatus = $this->db->insert('houdin_user_forgot_password',$setInsertArray);
            if($getInsertStatus == 1)
            {
                return array('message'=>'yes','token'=>$token);
            }
            else
            {
                return array('message'=>'no');
            }
        }
        else
        {
            return array('message'=>'email');
        }
    }
    public function updateUserPass($data)
    {

        $this->db->select('houdin_user_forgot_password_user_id')->from('houdin_user_forgot_password')->where('houdin_user_forgot_password_token',$data['pin']);
        $getUserData = $this->db->get()->result();
        if(count($getUserData) > 0)
        {
            $getUserId = $getUserData[0]->houdin_user_forgot_password_user_id;
            $getUserPass = $data['passwordData'];
            $letters1='abcdefghijklmnopqrstuvwxyz';
            $string1='';
            for($x=0; $x<3; ++$x)
            {
                $string1.=$letters1[rand(0,25)].rand(0,9);
            }
            $saltdata = password_hash($string1,PASSWORD_DEFAULT);
            $pass = crypt($getUserPass,$saltdata);
            $setDate = strtotime(date('Y-m-d h:i:s'));
            $setUpdatedArray = array('staff_password'=>$pass,'staff_password_salt'=>$saltdata,'update_date'=>$setDate);
            $this->db->where('staff_id',$getUserId);
            $getUserUpdateStatus  =$this->db->update('houdinv_staff_management',$setUpdatedArray);
            if($getUserUpdateStatus == 1)
            {
                $this->db->where('houdin_user_forgot_password_user_id',$getUserId);
                $getUpdateStatus = $this->db->delete('houdin_user_forgot_password');
                if($getUpdateStatus == 1)
                {
                    return array('message'=>'yes');
                }
                else
                {
                    return array('message'=>'no');
                }
            }
            else
            {
                return array('message'=>'no');
            }
        }
        else
        {
            return array('message'=>'pin');
        }
    }
}