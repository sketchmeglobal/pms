<?php

class Settings_m extends CI_Model {

    private $user_type = null;

    public function __construct() {
        parent::__construct();

        $this->load->library('grocery_CRUD');

        if($this->session->has_userdata('user_id')) { //if logged-in
            $this->user_type = $this->session->usertype;
        }
    }

    public function log_before_update($post_array,$primary_key){
        $insertArray = array(
            'table_name' => $this->table_name,
            'pk_id' => $primary_key,
            'action_taken'=>'edit', 
            'old_data' => json_encode($post_array),
            'user_id' => $this->session->user_id,
            'comment' => 'master'
        );
        if($this->db->insert('user_logs', $insertArray)){
            return true;
        }else{
            return false;
        }
    }
    
    public function database_backup_m() {

        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->dbutil();
        $prefs = array('format' => 'zip', 'filename' => 'Database-backup_' . date('Y-m-d_H-i'));
        $backup = $this->dbutil->backup($prefs);

        if (!write_file('./assets/admin_panel/backup/BD-backup_' . date('Y-m-d_H-i') . '.zip', $backup)) {
            echo "Error while creating auto database backup!";
        }else {
            //file path
            $file = './assets/admin_panel/backup/BD-backup_' . date('Y-m-d_H-i') . '.zip';
            //download file from directory
            force_download($file, NULL);
        }

    }

    public function meter_reading(){

        $user_id = $this->session->user_id;

        $crud = new grocery_CRUD();

        $crud->set_crud_url_path(base_url('admin_panel/Settings/meter_reading'));
        $crud->set_theme('datatables');
        $crud->set_subject('Meter Reading');
        $crud->set_table('meter_reading');
        
        $crud->unset_add();
        $crud->unset_read();
        $crud->unset_clone();
        $crud->unset_delete();

        $this->table_name = 'meter_reading';
        
        $crud->set_relation('user_id', 'users', 'username');

        $crud->callback_before_update(array($this,'log_before_update'));

        $crud->columns('meter_reading_option','user_id','status');
        $crud->fields('meter_reading_option','status');
        $crud->required_fields('meter_reading_option','status');
        
        $crud->field_type('user_id', 'hidden', $user_id);

        $output = $crud->render();

        //rending extra value to $output
        $output->tab_title = 'Meter Reading';
        $output->section_heading = 'Meter Reading <small>(Edit)</small>';
        $output->menu_name = 'Meter Reading';
        $output->add_button = '';

        return array('page'=>'common_v', 'data'=>$output); //loading common view page

    }
        
}