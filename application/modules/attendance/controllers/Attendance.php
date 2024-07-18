<?php


class Attendance extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
    }

    public function index($eid) {
        
        $data['attendance_status1'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'status' => 1, 'phase' => 1, 'today' => date('Y-m-d')))->num_rows();
        $data['attendance_status2'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'status' => 1, 'phase' => 2, 'today' => date('Y-m-d')))->num_rows();
        $data['attendance_status3'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'status' => 1, 'phase' => 3, 'today' => date('Y-m-d')))->num_rows();
        $data['attendance_status4'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'status' => 1, 'phase' => 4, 'today' => date('Y-m-d')))->num_rows();
        
        $data['user_details'] = $this->db
                    ->select('e_code, name')
                    ->join('employee_attendance', 'employees.e_id = employee_attendance.eid','left')
                    ->get_where('employees', array('e_id' => $eid))->result();
        $data['eid'] = $eid;
        
        if(count($data['user_details']) == 0){
            die('Not an acceptable link. Scan again.');
        }            
        
        if (!$this->agent->is_browser()){
                $agent = $this->agent->browser().' '.$this->agent->version();
                die($agent . ' not acceptable. Please try with a different browser.');
        }
        elseif ($this->agent->is_robot()){
                $agent = $this->agent->robot();
                die($agent . ' not acceptable. Please try with a proper device.');
        }
        elseif ($this->agent->is_mobile()){
                $agent = $this->agent->mobile();
        }
        else{
                $agent = 'Unidentified User Agent';
                die($agent . ' not acceptable. Please try with a different device or borwser.');
        }
        
        // echo $agent . ' '. $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
        
        if($agent == 'Android'){
        
            $upload_path = './assets/attendance/';
            $max_size = 3000;
            $file_type = 'jpg|jpeg|png|bmp';
            
            if($this->input->post('submit') == 'submit1'){
                
                $rv1 = $this->_upload_files($_FILES['file1'],$upload_path,$file_type,'file1', $max_size);
                
                
                
                /*$this->resizeImage($_FILES['file1'], $upload_path, $file_type, 'file1', $max_size);
                die();
                */
                
                
                if($rv1[0]['status'] == 'error'){
                    
                    die('Something went wrong while upload. Click on the back button of your browser and try again.');
                    
                }else{
                    
                    $file_name = $rv1[0]['filename'];
                    if($this->_compress_and_insert($upload_path, $file_name,$eid, 1)){
                        $data['attendance_status1'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'phase' => 1, 'status' => 1, 'today' => date('Y-m-d')))->num_rows();
                    }else{
                        $data['attendance_status1'] = 0;
                    }
                }
            }
            
            if($this->input->post('submit') == 'submit2'){
                
                $rv1 = $this->_upload_files($_FILES['file2'],$upload_path,$file_type,'file2', $max_size);
                
                if($rv1[0]['status'] == 'error'){
                    
                    die('Something went wrong while upload. Click on the back button of your browser and try again.');
                    
                }else{
                    
                    $file_name = $rv1[0]['filename'];
                    if($this->_compress_and_insert($upload_path, $file_name,$eid, 2)){
                        $data['attendance_status2'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'phase' => 2, 'status' => 1, 'today' => date('Y-m-d')))->num_rows();
                    }else{
                        $data['attendance_status2'] = 0;
                    }
                }
            }
            
            if($this->input->post('submit') == 'submit3'){
                
                $rv1 = $this->_upload_files($_FILES['file3'],$upload_path,$file_type,'file3', $max_size);
                
                if($rv1[0]['status'] == 'error'){
                    
                    die('Something went wrong while upload. Click on the back button of your browser and try again.');
                    
                }else{
                    
                    $file_name = $rv1[0]['filename'];
                    if($this->_compress_and_insert($upload_path, $file_name,$eid, 3)){
                        $data['attendance_status3'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'phase' => 3, 'status' => 1, 'today' => date('Y-m-d')))->num_rows();
                    }else{
                        $data['attendance_status4'] = 0;
                    }
                }
            }
            
            if($this->input->post('submit') == 'submit4'){
                
                $rv1 = $this->_upload_files($_FILES['file4'],$upload_path,$file_type,'file4', $max_size);
                
                if($rv1[0]['status'] == 'error'){
                    
                    die('Something went wrong while upload. Click on the back button of your browser and try again.');
                    
                }else{
                    
                    $file_name = $rv1[0]['filename'];
                    if($this->_compress_and_insert($upload_path, $file_name,$eid, 4)){
                        $data['attendance_status4'] = $this->db->get_where('employee_attendance', array('eid' => $eid, 'phase' => 4, 'status' => 1, 'today' => date('Y-m-d')))->num_rows();
                    }else{
                        $data['attendance_status4'] = 0;
                    }
                }
            }
            
            $this->load->view('capture', $data);
        }
        
        
    }
    
    private function _compress_and_insert($upload_path, $file_name, $eid, $phase){
      
        // print_r($rv1);
        $insertData = array(
            'today' => date('Y-m-d'),
            'eid' => $eid,
            'phase' => $phase,
            'upload_picture' => $file_name
        );
        if($this->db->insert('employee_attendance', $insertData)){
            return 1;
        }else{
            return 0;
        }
        
    }

    private function _upload_files($files, $upload_path, $file_type, $user_file_name, $max_size){

        date_default_timezone_set("Asia/Kolkata");  

        $uploadedFileData = array();

        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => $file_type,
            'max_size' => $max_size,
            'encrypt_name' => TRUE
        );

        $this->load->library('upload', $config);

        $_FILES['file']['name']       = $_FILES[$user_file_name]['name'];
        $_FILES['file']['type']       = $_FILES[$user_file_name]['type'];
        $_FILES['file']['tmp_name']   = $_FILES[$user_file_name]['tmp_name'];
        $_FILES['file']['error']      = $_FILES[$user_file_name]['error'];
        $_FILES['file']['size']       = $_FILES[$user_file_name]['size'];

        // $config['file_name'] = date('His') .'_'. $image;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            
            $this->load->library('image_lib');
            $image_data =   $this->upload->data();
            $configer =  array(
                                'image_library' => 'gd2',
                                'source_image'  =>  $image_data['full_path'],
                                'maintain_ratio'=>  TRUE,
                                'width'         =>  600,
                                'height'        =>  600,
                                'master_dim'    => 'width',
                                'quality'       =>  "100%",
                              );
            $this->image_lib->clear();
            $this->image_lib->initialize($configer);
            if($this->image_lib->resize()){
                $new_array[] = array(
                'filename' => $image_data['file_name'], 
                'status' => 'success',
                'msg' => 'OK'
            );

            $final_array = array_merge($uploadedFileData, $new_array);
            }
            
            //$imageData = $this->upload->data();

            

        } else {
            $new_array[] = array(
                'filename' => null, 
                'status' => 'error',
                'msg' => 'Type or Size Mismatch'
            );

            $final_array = array_merge($uploadedFileData, $new_array);
        }

        return $final_array;
    }
    
    /**/
} 