<?php

class Master_m extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function fetch_permission_matrix($user_id, $m_id){
        $nr = $this->db->get_where('user_permission', array('user_id' => $user_id, 'm_id' => 3))->num_rows();
        if(count($nr) == 0){
            $this->session->set_flashdata('title', 'Not-set!');
            $this->session->set_flashdata('msg', 'Permission not set. Please contact admin for permission.');
            redirect(base_url('admin/dashboard'));
        }else{
            return $this->db->get_where('user_permission', array('user_id' => $user_id, 'm_id' => $m_id))->result();
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

    public function check_and_log_before_delete($primary_key){
        // echo $this->reference_table_name . ' || ' . $this->reference_pk_field_name . ' || ' . $primary_key;die;
        $item_exists = 0;
        foreach($this->reference_array as $ra){
            $nr = $this->db->get_where($ra['tbl_name'], array($ra['tbl_pk_fld'] => $primary_key))->num_rows();
            if($nr > 0){
                $item_exists = 1;
            }
        }
        // print_r($this->reference_array);die;        

        if($item_exists > 0){
            return false;
        } else{
            $user_data = $this->db->where($this->pk_field_name, $primary_key)->get($this->table_name)->row();
            $insertArray = array(
                'table_name' => $this->table_name,
                'pk_id' => $primary_key,
                'action_taken'=>'delete', 
                'old_data' => json_encode($user_data),
                'user_id' => $this->session->user_id,
                'comment' => 'master'
            );
            if($this->db->insert('user_logs', $insertArray)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function units() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/units'));
            $crud->set_theme('datatables');
            $crud->set_subject('Unit');
            $crud->set_table('units');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 9);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }            
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'units';
            $this->pk_field_name = 'u_id';
            // reference table values for checking
            $this->reference_array = array(
                array(
                    "tbl_name" => "item_groups",
                    "tbl_pk_fld" => "u_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            //$crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions 

            $crud->columns('unit','info','status');
            $crud->fields('unit','info','status','user_id');
            $crud->required_fields('unit','status');
            $crud->unique_fields(array('unit'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Units';
            $output->section_heading = 'Units <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Units';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function product_category() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/product_category'));
            $crud->set_theme('datatables');
            $crud->set_subject('Product Category');
            $crud->set_table('product_category');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
                       
            
            $this->table_name = 'product_category';
            $crud->callback_before_update(array($this,'log_before_update'));

            $crud->columns('category_name','information','status');
            $crud->fields('category_name','information','status');
            $crud->required_fields('category_name','status');
            $crud->unique_fields(array('category_name'));

            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Products Category';
            $output->section_heading = 'Products  Category<small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Products Category';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }    

    public function sizes() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/sizes'));
            $crud->set_theme('datatables');
            $crud->set_subject('Product');
            $crud->set_table('sizes');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 10);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'sizes';
            $this->pk_field_name = 'sz_id';
            // reference table values for checking 
            $this->reference_array = array(
                array(
                    "tbl_name" => "item_master",
                    "tbl_pk_fld" => "sz_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            //$crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions 

            $crud->columns('pc_id','product_name','size','paper_gsm','paper_bf','rate_per_unit','employee_payment_rate_per_unit','status');
            $crud->fields('pc_id','product_name','size','info','paper_gsm','paper_bf','rate_per_unit','hsn_code','employee_payment_rate_per_unit','status','user_id');
            $crud->required_fields('pc_id','size','paper_gsm','paper_bf','rate_per_unit','status');
            $crud->unique_fields(array('size'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->set_relation('pc_id','product_category','category_name');

            $crud->display_as('pc_id', 'Product Category');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Products';
            $output->section_heading = 'Products <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Products';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function shapes() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/shapes'));
            $crud->set_theme('datatables');
            $crud->set_subject('Shape');
            $crud->set_table('shapes');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 11);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

             // callback conditions
             $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
             // current table values 
             $this->table_name = 'shapes';
             $this->pk_field_name = 'sh_id';
             // reference table values for checking  
             $this->reference_array = array(
                array(
                    "tbl_name" => "item_master",
                    "tbl_pk_fld" => "sh_id",
                )
            );
             // $crud->callback_after_insert(array($this,'log_user_after_insert'));
             // $crud->callback_after_update(array($this, 'log_user_after_update'));
             $crud->callback_before_update(array($this,'log_before_update'));
             $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
             // callback conditions 

            $crud->columns('shape','info','status');
            $crud->fields('shape','info','status','user_id');
            $crud->required_fields('shape','status');
            $crud->unique_fields(array('shape'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Shapes';
            $output->section_heading = 'Shapes <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Shapes';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function item_groups() {
        $user_id = $this->session->user_id;
        
        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/item_groups'));
            $crud->set_theme('datatables');
            $crud->set_subject('Item Group');
            $crud->set_table('item_groups');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 3);
            
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter 

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'item_groups';
            $this->pk_field_name = 'ig_id';
            // reference table values for checking  
            // $this->reference_table_name = 'item_master';
            // $this->reference_pk_field_name = 'ig_id';
            
            $this->reference_array = array(
                array(
                    "tbl_name" => "item_master",
                    "tbl_pk_fld" => "ig_id",
                )
            );

            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions 

            $crud->columns('ig_code','group_name','u_id','status');
            $crud->fields('ig_code','group_name','u_id','value','sort_order','status','user_id');
            $crud->required_fields('ig_code','group_name','u_id','status');
            $crud->unique_fields(array('ig_code'));

            $crud->display_as('ig_code', 'Item Group Code');
            $crud->display_as('group_name', 'Group Name');
            $crud->display_as('u_id', 'Unit');

            $crud->set_relation('u_id', 'units', 'unit');
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Item Groups';
            $output->section_heading = 'Item Groups <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Item Groups';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function ajax_unit_on_item_group(){
        $item_group_id = $this->input->post('group_code');
        return $this->db->get_where('item_groups', array('ig_id' => $item_group_id))->result()[0]->u_id;
    }

    public function item_master() {
        $data = '';
        return array('page'=>'master/item_master_list_v', 'data'=>$data);
    }

    /*public function add_item() {
        $data['item_groups'] = $this->db->get_where('item_groups', array('status'=>'1'))->result_array();
        $data['sizes'] = $this->db->get_where('sizes', array('status'=>'1'))->result_array();
        $data['shapes'] = $this->db->get_where('shapes', array('status'=>'1'))->result_array();
        $data['units'] = $this->db->get_where('units', array('status'=>'1'))->result_array();

        return array('page'=>'master/item_master_add_v', 'data'=>$data);
    }

    public function form_add_item() {
        $data_insert['ig_id'] = $this->input->post('item_group');
        $data_insert['im_code'] = $this->input->post('item_code');
        $data_insert['sz_id'] = $this->input->post('size');
        $data_insert['sh_id'] = $this->input->post('shape');
        $data_insert['u_id'] = $this->input->post('unit');
        $data_insert['info_1'] = $this->input->post('desc1');
        $data_insert['info_2'] = $this->input->post('desc2');
        $data_insert['item'] = $this->input->post('item_name');
        $data_insert['type'] = $this->input->post('item_type');
        $data_insert['enlist_jobber'] = $this->input->post('jobber');
        $data_insert['enlist_costing'] = $this->input->post('show_in_costing');
        $data_insert['thick'] = $this->input->post('thick');
        $data_insert['buy_code'] = $this->input->post('buy_code');
        // $data_insert['sell_code'] = $this->input->post('sell_code');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;

        $this->db->insert('item_master', $data_insert);
        $data['insert_id'] = $this->db->insert_id();

        $data['type'] = 'success';
        $data['msg'] = 'Item added successfully.';
        return $data;
    }

    public function edit_item($im_id) {
        $data['item_groups'] = $this->db->get_where('item_groups', array('status'=>'1'))->result_array();
        $data['sizes'] = $this->db->get_where('sizes', array('status'=>'1'))->result_array();
        $data['shapes'] = $this->db->get_where('shapes', array('status'=>'1'))->result_array();
        $data['units'] = $this->db->get_where('units', array('status'=>'1'))->result_array();
		$data['item'] = $this->db->get_where('item_master', array('im_id'=>$im_id))->row();
		
        $data['items'] = $this->db->get_where('item_master', array('status' => 1))->result_array();
		$data['all_colors'] = $this->db->get_where('colors', array('status' => 1))->result_array();
		
        $data['colors'] = $this->db->where('colors.c_id NOT IN (select c_id from item_dtl WHERE `item_dtl`.`im_id` = '.$im_id .')',NULL,FALSE)->get_where('colors', array('colors.status' => 1))->result_array();
        $data['edit_colors'] = $this->db->get_where('colors', array('colors.status' => 1))->result_array();
        // echo $this->db->get_compiled_select('colors');
        // exit();

        $this->db->join('acc_groups', 'acc_groups.ag_id = acc_master.ag_id', 'left');
        // $this->db->where('acc_groups.group_name', 'Sundry Debtors');
        $this->db->where('acc_master.status', '1');
        $data['acc_master'] = $this->db->get('acc_master')->result_array(); 

        return array('page'=>'master/item_master_edit_v', 'data'=>$data);
    }

    public function form_edit_item() {
        $item_id = $this->input->post('item_id');
        $data_update['ig_id'] = $this->input->post('item_group');
        $data_update['im_code'] = $this->input->post('item_code');
        $data_update['sz_id'] = $this->input->post('size');
        $data_update['sh_id'] = $this->input->post('shape');
        $data_update['u_id'] = $this->input->post('unit');
        $data_update['info_1'] = $this->input->post('desc1');
        $data_update['info_2'] = $this->input->post('desc2');
        $data_update['item'] = $this->input->post('item_name');
        $data_update['type'] = $this->input->post('item_type');
        $data_update['enlist_jobber'] = $this->input->post('jobber');
        $data_update['enlist_costing'] = $this->input->post('show_in_costing');
        $data_update['thick'] = $this->input->post('thick');
        $data_update['buy_code'] = $this->input->post('buy_code');
        // $data_update['sell_code'] = $this->input->post('sell_code');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;
        

        $this->db->where('im_id', $item_id);
        $this->db->update('item_master', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Item updated successfully.';
        return $data;
    }

    public function form_add_item_buy_code() {
		$data_insert['ig_id'] = $this->input->post('ig_id');
        $data_insert['im_id'] = $this->input->post('item_id');
        $data_insert['am_id'] = $this->input->post('account_master_code');
		$data_insert['main_color_id'] = $this->input->post('main_color_id');		
       // $data_insert['buying_code'] = $this->input->post('buying_code');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;
		
		$data_insert['buyer_im_id'] = $this->input->post('im_id');
		$data_insert['customer_item_code'] = $this->input->post('customer_item_code');
		$data_insert['c_id'] = $this->input->post('c_id');
		$data_insert['customer_colour_code'] = $this->input->post('customer_colour_code');
		
        
        $this->db->insert('item_buying_codes', $data_insert);
		
		//echo $this->db->last_query();die;
        $data['type'] = 'success';
        $data['msg'] = 'Item buying codes added successfully.';
        return $data;
    }

    public function form_edit_item_buy_code() {
        $ibc_id = $this->input->post('ibc_id');
        $data_update['ibc_id'] = $ibc_id;
        $data_update['buying_code'] = $this->input->post('buying_code_edit');
        $data_update['status'] = $this->input->post('status');
		
		$data_update['buyer_im_id'] = $this->input->post('im_id_edit');
		$data_update['customer_item_code'] = $this->input->post('customer_item_code_edit');
		$data_update['c_id'] = $this->input->post('c_id_edit');
		$data_update['customer_colour_code'] = $this->input->post('customer_colour_code_edit');
		
        $data_update['user_id'] = $this->session->user_id;

        $this->db->where('ibc_id', $ibc_id)->update('item_buying_codes', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Item buying codes updated successfully.';
        return $data;
    }*/

    public function form_add_item_color() {
        $data_insert['im_id'] = $this->input->post('item_id');
        $data_insert['c_id'] = $this->input->post('color');
        $data_insert['opening_stock'] = $this->input->post('opening_stock');
        $data_insert['reorder_qnty'] = $this->input->post('reorder');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;
        //if image uploaded
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/item_img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 1024;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $data_insert['img'] = $uploaded_data['file_name'];
            }
        }

        $this->db->insert('item_dtl', $data_insert);

        $data['type'] = 'success';
        $data['msg'] = 'Item color added successfully.';
        return $data;
    }

    public function form_edit_item_color() {
        $item_dtl_id = $this->input->post('item_dtl_id');
        // $data_update['c_id'] = $this->input->post('color');
        $data_update['opening_stock'] = $this->input->post('opening_stock_edit');
        $data_update['reorder_qnty'] = $this->input->post('reorder');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;
        //if image uploaded
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/item_img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 1024;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $data_update['img'] = $uploaded_data['file_name'];

                //deleting old file from server
                $old_img_name = $this->db->get_where('item_dtl', array('id_id' => $item_dtl_id))->row()->img;
                if ($old_img_name) {
                    $this->load->helper("file");
                    $path = 'assets/admin_panel/img/item_img/' . $old_img_name;
                    unlink($path);
                }
            }
        }

        $this->db->where('id_id', $item_dtl_id);
        $this->db->update('item_dtl', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Item color updated successfully.';
        return $data;
    }

    public function form_add_item_color_rate() {
        $data_insert['id_id'] = $this->input->post('item_dtl_id');
        $data_insert['am_id'] = $this->input->post('supplier');
        $data_insert['gst_percentage'] = $this->input->post('gst');
        $data_insert['purchase_rate'] = $this->input->post('pur_rate');
        $data_insert['cost_rate'] = $this->input->post('cost_rate');
        $data_insert['effective_date'] = $this->input->post('eff_date');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;

        $this->db->insert('item_rates', $data_insert);

        $data['type'] = 'success';
        $data['msg'] = 'Item color rate added successfully.';
        return $data;
    }

    public function form_edit_item_color_rate() {
        $item_rate_id = $this->input->post('item_rate_id');
        $data_update['am_id'] = $this->input->post('supplier');
        $data_update['gst_percentage'] = $this->input->post('gst');
        $data_update['purchase_rate'] = $this->input->post('pur_rate');
        $data_update['cost_rate'] = $this->input->post('cost_rate');
        $data_update['effective_date'] = $this->input->post('eff_date');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;

        $this->db->where('ir_id', $item_rate_id);
        $this->db->update('item_rates', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Item color rate updated successfully.';
        return $data;
    }

    public function ajax_unique_item_code() {
        $item_id = $this->input->post('item_id');
        $item_code = $this->input->post('item_code');
        $item_rs = $this->db->get_where('item_master', array('im_id !='=>$item_id, 'im_code'=>$item_code))->result();

        if(count($item_rs) == 0) {
            $data = 'true';
        } else {
            $data = 'Item code already used.';
        }

        return $data;
    }

    public function ajax_unique_item_name() {
        $item_id = $this->input->post('item_id');
        $item_name = $this->input->post('item_name');
        $item_rs = $this->db->get_where('item_master', array('im_id !='=>$item_id, 'item' => $item_name))->result();

        if(count($item_rs) == 0) {
            $data = 'true';
        } else {
            $data = 'Item name already used.';
        }

        return $data;
    }

    public function ajax_unique_item_buy_code() {
        $item_id = $this->input->post('item_id');
        $am_id = $this->input->post('account_master_code');
        
        $item_rs = $this->db->get_where('item_buying_codes', array('im_id'=>$item_id, 'am_id' => $am_id))->num_rows();

        if($item_rs == 0) {
            $data = 'true';
        } else {
            $data = 'Code already added for this Buyer.';
        }

        return $data;
    }

    public function ajax_unique_item_color() {
        $item_id = $this->input->post('item_id');
        $item_dtl_id = $this->input->post('item_dtl_id');
        $color_id = $this->input->post('color');
        $item_rs = $this->db->get_where('item_dtl', array('im_id'=>$item_id, 'id_id !='=>$item_dtl_id, 'c_id' => $color_id))->result();

        if(count($item_rs) == 0) {
            $data = 'true';
        } else {
            $data = 'This color already added.';
        }

        return $data;
    }

    public function ajax_fetch_buy_code_details() {
        $item_buying_code_id = $this->input->post('item_buying_code_id');
        // $im_id = $this->input->post('item_id');
        
        $this->db->select('acc_master.name, item_buying_codes.*');
            $this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left');
            $rs = $this->db->get_where('item_buying_codes', array('item_buying_codes.ibc_id'=>$item_buying_code_id))->result();
            return $rs;
    }

    public function ajax_fetch_item_color() {
        $item_dtl_id = $this->input->post('item_dtl_id');

        $this->db->select('id_id,item_dtl.c_id,opening_stock,reorder_qnty,item_dtl.status,colors.color');
        $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
        $item_dtl_row = $this->db->get_where('item_dtl', array('id_id'=>$item_dtl_id))->row();

        return $item_dtl_row;
    }

    public function ajax_unique_supp_item_color_rate_eff_date() {
        $item_dtl_id = $this->input->post('item_dtl_id');
        $supplier = $this->input->post('supplier');
        $eff_date = $this->input->post('eff_date');
        $item_rate_id = $this->input->post('item_rate_id');

        $this->db->where('id_id', $item_dtl_id);
        $this->db->where('am_id', $supplier);
        $this->db->where('effective_date', $eff_date);
        $this->db->where('ir_id !=', $item_rate_id);
        $item_rate_rs = $this->db->get('item_rates')->result();

        if(count($item_rate_rs) == 0) {
            $data = 'true';
        } else {
            $data = 'This effective date already added, for that item color, with selected supplier.';
        }

        return $data;
    }

    public function ajax_fetch_item_rate() {
        $item_rate_id = $this->input->post('item_rate_id');

        $this->db->select('ir_id,am_id,purchase_rate,cost_rate,gst_percentage,effective_date,status');
        $item_rate_row = $this->db->get_where('item_rates', array('ir_id'=>$item_rate_id))->row();

        return $item_rate_row;
    }

    public function countries() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/countries'));
            $crud->set_theme('datatables');
            $crud->set_subject('Country');
            $crud->set_table('countries');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 13);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'countries';
            $this->pk_field_name = 'c_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "acc_master",
                    "tbl_pk_fld" => "c_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions 

            $crud->columns('country','c_code','status');
            $crud->fields('country','c_code','status','user_id');
            $crud->required_fields('country','c_code','status');
            $crud->unique_fields(array('country','c_code'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('c_code', 'Country Code');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Countries';
            $output->section_heading = 'Countries <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Countries';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function stations() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/stations'));
            $crud->set_theme('datatables');
            $crud->set_subject('Station');
            $crud->set_table('stations');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 13);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'stations';
            $this->pk_field_name = 's_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "acc_master",
                    "tbl_pk_fld" => "s_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions

            $crud->columns('c_id','station','status');
            $crud->fields('c_id','station','status','user_id');
            $crud->required_fields('c_id','station','status');
            $crud->unique_fields(array('station'));

            $crud->set_relation('c_id', 'countries', 'country');
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('c_id', 'Country');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Stations';
            $output->section_heading = 'Stations <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Stations';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function currencies() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/currencies'));
            $crud->set_theme('datatables');
            $crud->set_subject('Currency');
            $crud->set_table('currencies');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 15);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'currencies';
            $this->pk_field_name = 'cur_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "acc_master",
                    "tbl_pk_fld" => "cur_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions

            $crud->columns('c_id','currency','currency_sign','info','status');
            $crud->fields('c_id','currency','currency_sign','info','status','user_id','CURR_SRATE','CURR_BRATE');
            $crud->required_fields('c_id','currency','currency_sign','status');
            $crud->unique_fields(array('c_id'));

            $crud->set_relation('c_id', 'countries', 'country');
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('c_id', 'Country');
            $crud->display_as('currency_sign', 'Currency Sign');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Currencies';
            $output->section_heading = 'Currencies <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Currencies';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function colors() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/colors'));
            $crud->set_theme('datatables');
            $crud->set_subject('Color');
            $crud->set_table('colors');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 12);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

             // callback conditions
             $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
             // current table values 
             $this->table_name = 'colors';
             $this->pk_field_name = 'c_id';
             // reference table values for checking  
             $this->reference_array = array(
                array(
                    "tbl_name" => "item_dtl",
                    "tbl_pk_fld" => "c_id",
                ),
                array(
                    "tbl_name" => "article_dtl",
                    "tbl_pk_fld" => "lth_color_id",
                ),
                array(
                    "tbl_name" => "article_dtl",
                    "tbl_pk_fld" => "fit_color_id",
                )
            );
             // $crud->callback_after_insert(array($this,'log_user_after_insert'));
             // $crud->callback_after_update(array($this, 'log_user_after_update'));
             $crud->callback_before_update(array($this,'log_before_update'));
             //$crud->callback_before_delete(array($this,'check_and_log_before_delete'));
             // callback conditions 

            $crud->columns('color','c_code','status');
            $crud->fields('color','c_code','status','user_id');
            $crud->required_fields('color','status');
            $crud->unique_fields(array('color','c_code'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('c_code', 'Color Code');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Colors';
            $output->section_heading = 'Colors <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Colors';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function account_groups() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/account_groups'));
            $crud->set_theme('datatables');
            $crud->set_subject('Account Group');
            $crud->set_table('acc_groups');
            $crud->unset_read();
            $crud->unset_clone();

             // permission setter 
             $result_set = $this->fetch_permission_matrix($user_id, $m_id = 7);
             // echo '<pre>', print_r($result_set), '</pre>';die;
             if($result_set[0]->block_permission){
                 $this->session->set_flashdata('title', 'No-Permission!');
                 $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                 redirect(base_url('admin/dashboard'));
             }else{
                 if($result_set[0]->add_permission == 0){
                     $crud->unset_add();
                 }
                 if($result_set[0]->edit_permission == 0){
                     $crud->unset_edit();
                 }
                 if($result_set[0]->delete_permission == 0){
                     $crud->unset_delete();
                 }
                 if($result_set[0]->print_permission == 0){
                     $crud->unset_print();
                 }
                 if($result_set[0]->download_permission == 0){
                     $crud->unset_export();
                 }
             }
             // permission setter 

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'acc_groups';
            $this->pk_field_name = 'ag_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "acc_master",
                    "tbl_pk_fld" => "ag_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions 


            $crud->columns('group_name','type','type_side','status');
            $crud->fields('group_name','type','type_side','status','user_id');
            $crud->required_fields('group_name','type','type_side','status');
            $crud->unique_fields(array('group_name'));

            $crud->field_type('type', 'dropdown', array('Profit & Loss'=>'Profit & Loss','Balance Sheet'=>'Balance Sheet'));
            $crud->field_type('type_side', 'dropdown', array('Income'=>'Income','Expenditure'=>'Expenditure','Assets'=>'Assets','Liabilities'=>'Liabilities'));
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('group_name', 'Group Name');
            $crud->display_as('type_side', 'Type Side');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Account Groups';
            $output->section_heading = 'Account Groups <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Account Groups';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function account_master() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/account_master'));
            $crud->set_theme('datatables');
            $crud->set_subject('Supplier or Buyer');
            $crud->set_table('acc_master');
            $crud->unset_read();
            $crud->unset_clone();

             // permission setter 
             $result_set = $this->fetch_permission_matrix($user_id, $m_id = 8);
             // echo '<pre>', print_r($result_set), '</pre>';die;
             if($result_set[0]->block_permission){
                 $this->session->set_flashdata('title', 'No-Permission!');
                 $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                 redirect(base_url('admin/dashboard'));
             }else{
                 if($result_set[0]->add_permission == 0){
                     $crud->unset_add();
                 }
                 if($result_set[0]->edit_permission == 0){
                     $crud->unset_edit();
                 }
                 if($result_set[0]->delete_permission == 0){
                     $crud->unset_delete();
                 }
                 if($result_set[0]->print_permission == 0){
                     $crud->unset_print();
                 }
                 if($result_set[0]->download_permission == 0){
                     $crud->unset_export();
                 }
             }
             // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'acc_master';
            $this->pk_field_name = 'ag_id';
            $crud->callback_before_update(array($this,'log_before_update'));
            //$crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions

            $crud->columns('name','am_code','phone','status');
            $crud->unset_fields('create_date','modify_date');
            $crud->required_fields('ag_id','name', 'status');
            $crud->unique_fields(array('name','short_name','am_code'));

            /*$crud->set_relation('ag_id', 'acc_groups', 'group_name');
            $crud->set_relation('c_id', 'countries', 'country');
            $crud->set_relation('s_id', 'stations', 'station');
            $crud->set_relation('cur_id', 'currencies', 'currency');
            $crud->set_relation('buyer', 'acc_master', 'name');
            $crud->field_type('acc_type', 'dropdown', array('None'=>'None','Cutter'=>'Cutter','Fabricator'=>'Fabricator','Skiver'=>'Skiver'));*/	
			
			$crud->set_relation('mill_id', 'mill', 'mill_name');			
			$crud->display_as('mill_id', 'Mill Name');
			
			$crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
			$crud->display_as('mill_id', 'Mill Name');
			
            $crud->field_type('supplier_buyer', 'true_false', array('0'=>'Supplier','1'=>'Buyer'));
            $crud->display_as('supplier_buyer', 'Supplier or Buyer');

            //$crud->display_as('ag_id', 'Account Group');
            $crud->display_as('short_name', 'Short Name');
            $crud->display_as('am_code', 'Code');
            $crud->display_as('email_id', 'Email ID');
            $crud->display_as('vat_no', 'GSTIN');
			$crud->display_as('proprietor', 'Contact person name');
			$crud->field_type('user_id', 'hidden', $user_id);
            /*$crud->display_as('c_id', 'Country');
            $crud->display_as('s_id', 'Station');
            $crud->display_as('cur_id', 'Currency');
            $crud->display_as('acc_type', 'Type');
            $crud->display_as('courier_address', 'Courier Address');
			
			$crud->add_action('Declaration', '', 'admin/account-declaration','ui-icon-plus');*/

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Supplier or Buyer';
            $output->section_heading = 'Supplier or Buyer <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Supplier or Buyer';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }
	
	public function account_declaration($am_id) {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/account_declaration/'.$am_id));
            $crud->set_theme('datatables');
            $crud->set_subject('Account Declaration');
			$crud->where('acc_master_declaration.am_id', $am_id);
            $crud->set_table('acc_master_declaration');
			
            $crud->unset_read();
            $crud->unset_clone();

             // permission setter 
             $result_set = $this->fetch_permission_matrix($user_id, $m_id = 8);
             // echo '<pre>', print_r($result_set), '</pre>';die;
             if($result_set[0]->block_permission){
                 $this->session->set_flashdata('title', 'No-Permission!');
                 $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                 redirect(base_url('admin/dashboard'));
             }else{
                 if($result_set[0]->add_permission == 0){
                     $crud->unset_add();
                 }
                 if($result_set[0]->edit_permission == 0){
                     $crud->unset_edit();
                 }
                 if($result_set[0]->delete_permission == 0){
                     $crud->unset_delete();
                 }
                 if($result_set[0]->print_permission == 0){
                     $crud->unset_print();
                 }
                 if($result_set[0]->download_permission == 0){
                     $crud->unset_export();
                 }
             }
             // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // callback conditions

            $crud->columns('master_account','declaration_subject','declaration_description','status');
			$crud->callback_column('master_account',array($this,'master_account_name'));
			
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);
			$crud->field_type('am_id', 'hidden', $am_id);
			
            $crud->display_as('declaration_subject', 'Declaration Subject');
            $crud->display_as('declaration_description', 'Declaration Description');			
			
            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Account Declaration';
            $output->section_heading = 'Account Declaration <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Account Declaration';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

	public function master_account_name($value, $row){
		$am_id = $row->am_id;
		return $this->db->select('name')->get_where('acc_master', array('am_id' => $am_id))->result()[0]->name;
	}

    public function charges() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/charges'));
            $crud->set_theme('datatables');
            $crud->set_subject('Charge');
            $crud->set_table('charges');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 16);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'charges';
            $this->pk_field_name = 'c_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "article_costing_charges",
                    "tbl_pk_fld" => "c_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions

            $crud->columns('charge_group','charge','amount','percentage','fix','status');
            $crud->fields('charge_group','charge','amount','percentage','fix','status','user_id');
            $crud->required_fields('charge_group','charge','fix','status');
            $crud->unique_fields(array('charge'));

            $crud->field_type('charge_group', 'dropdown', array('Charge'=>'Charge','Overhead'=>'Overhead','Commission'=>'Commission'));
            $crud->field_type('fix', 'dropdown', array('No'=>'No','Yes'=>'Yes'));
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('charge_group', 'Charge Group');
            $crud->display_as('charge', 'Charge Name');
            $crud->display_as('fix', 'Fix Charge');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Charges';
            $output->section_heading = 'Charges <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Charges';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function departments() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/departments'));
            $crud->set_theme('datatables');
            $crud->set_subject('Department');
            $crud->set_table('departments');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 17);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'departments';
            $this->pk_field_name = 'd_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "employees",
                    "tbl_pk_fld" => "d_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            //$crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions

            $crud->columns('department','status');
            $crud->fields('department','status','user_id');
            $crud->required_fields('department','status');
            $crud->unique_fields(array('department'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Departments';
            $output->section_heading = 'Departments <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Departments';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }
	
	public function transporter() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/transporter'));
            $crud->set_theme('datatables');
            $crud->set_subject('Transporter');
            $crud->set_table('transporter');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 17);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            /*$this->table_name = 'transporter';
            $this->pk_field_name = 'transporter_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "acc_master",
                    "tbl_pk_fld" => "mill_id",
                )
            );
            $crud->callback_before_update(array($this,'log_before_update'));*/

            $crud->columns('transporter_name', 'transporter_contact_person', 'transporter_contact_phone', 'transporter_contact_cell','status');
            $crud->fields('transporter_name', 'transporter_contact_person', 'transporter_contact_phone', 'transporter_contact_cell', 'transporter_address', 'status','user_id');
            $crud->required_fields('transporter_name', 'transporter_contact_phone','status');
            $crud->unique_fields(array('transporter_name'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Transporter';
            $output->section_heading = 'Transporter <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Transporter';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }
	
	public function mill() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/mill'));
            $crud->set_theme('datatables');
            $crud->set_subject('Mill');
            $crud->set_table('mill');
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 17);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'mill';
            $this->pk_field_name = 'mill_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "acc_master",
                    "tbl_pk_fld" => "mill_id",
                )
            );
            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            //$crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions

            $crud->columns('mill_name', 'mill_short_code', 'mill_contact_person', 'mill_contact_phone', 'mill_contact_cell', 'status');
            $crud->fields('mill_name', 'mill_short_code', 'mill_contact_person', 'mill_contact_phone', 'mill_contact_cell', 'mill_address', 'status','user_id');
            $crud->required_fields('mill_name', 'mill_short_code', 'status');
            $crud->unique_fields(array('mill_name'));

            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Mill';
            $output->section_heading = 'Mill <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Mill';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function article_groups() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/article_groups'));
            $crud->set_theme('datatables');
            $crud->set_subject('Article Group');
            $crud->set_table('article_groups');
            // $crud->unset_export();
            // $crud->unset_print();
            $crud->unset_read();
            $crud->unset_clone();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 5);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            // permission setter 

            // callback conditions
            $crud->set_lang_string('delete_error_message', 'Unsuccessful! Data exsits in another table.');
            // current table values 
            $this->table_name = 'article_groups';
            $this->pk_field_name = 'ag_id';
            // reference table values for checking  
            $this->reference_array = array(
                array(
                    "tbl_name" => "article_master",
                    "tbl_pk_fld" => "ag_id",
                )
            );

            // $crud->callback_after_insert(array($this,'log_user_after_insert'));
            // $crud->callback_after_update(array($this, 'log_user_after_update'));
            $crud->callback_before_update(array($this,'log_before_update'));
            $crud->callback_before_delete(array($this,'check_and_log_before_delete'));
            // callback conditions 

            $crud->columns('d_id','group_name','status');
            $crud->fields('d_id','group_name','status','user_id');
            $crud->required_fields('d_id','group_name','status');
            $crud->unique_fields(array('group_name'));

            $crud->set_relation('d_id', 'departments', 'department');
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('user_id', 'hidden', $user_id);

            $crud->display_as('d_id', 'Department');
            $crud->display_as('group_name', 'Group Name');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Article Groups';
            $output->section_heading = 'Article Groups <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Article Groups';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function employees() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/employees'));
            $crud->set_theme('datatables');
            $crud->set_subject('Employee');
            $crud->set_table('employees');
            // $crud->order_by('e_id','asc');
            $crud->unset_read();
            $crud->unset_clone();
            //$crud->unset_delete();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 18);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            
            // permission setter

            //$crud->columns('e_code','name', 'gender', 'dob', 'contact_phone', 'employee_email', 'emp_adhar_card_number', 'father_name', 'address', 'doj');
			$crud->columns('name','e_code', 'gender', 'dob', 'contact_phone', 'employee_email', 'emp_adhar_card_number');
            $crud->unset_fields('create_date','modify_date');
            $crud->required_fields('e_code','name', 'gender', 'dob', 'emp_adhar_card_number', 'father_name', 'address', 'doj');
            $crud->unique_fields(array('e_code'));

            $crud->set_relation('d_id', 'departments', 'department');
            //$crud->field_type('working_place', 'dropdown', array('Office'=>'Office','Factory'=>'Factory'));
            $crud->field_type('gender', 'dropdown', array('Male'=>'Male','Female'=>'Female','Other'=>'Other'));
            //$crud->field_type('esi', 'true_false', array('0'=>'No','1'=>'Yes'));
            //$crud->field_type('pf', 'true_false', array('0'=>'No','1'=>'Yes'));
            $crud->field_type('status', 'true_false', array('0'=>'Disable','1'=>'Enable'));
            $crud->field_type('due_amount', 'hidden');
			$crud->field_type('user_id', 'hidden', $user_id);
            $crud->set_field_upload('picture', 'assets/admin_panel/img/employee_img', 'jpg|jpeg|png|bpm');

            $crud->display_as('e_code', 'Employee Code');
			$crud->display_as('emp_adhar_card_number', 'Adhar Card Number');
           // $crud->display_as('working_place', 'Working Place');
            $crud->display_as('father_name', 'Father Name');
            $crud->display_as('dob', 'Date of Birth');
            $crud->display_as('doj', 'Date of Joining');
            $crud->display_as('d_id', 'Department');
			
            if($crud->getState() == 'add'){
                $last_emp_code = $this->db->order_by('e_id', 'desc')->limit(1)->get('employees')->result()[0]->e_code;
                $emp_code = 'E'.(substr($last_emp_code, 1) + 1);
            }else{
                $emp_code = '';
            }

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Employees';
            $output->section_heading = 'Employees <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Employees';
            $output->add_button = '';
            $output->state = $crud->getState();
            $output->emp_code = $emp_code;

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function menusetting() {
        $user_id = $this->session->user_id;

        try{
            $crud = new grocery_CRUD();
            $crud->set_crud_url_path(base_url('admin_panel/Master/menusetting'));
            $crud->set_theme('datatables');
            $crud->set_subject('Menu Setting');
            $crud->set_table('menu_setting');
            $crud->unset_read();
            $crud->unset_clone();
            //$crud->unset_delete();

            // permission setter 
            $result_set = $this->fetch_permission_matrix($user_id, $m_id = 18);
            // echo '<pre>', print_r($result_set), '</pre>';die;
            if($result_set[0]->block_permission){
                $this->session->set_flashdata('title', 'No-Permission!');
                $this->session->set_flashdata('msg', 'Sorry! You do not have permission to view this page.');
                redirect(base_url('admin/dashboard'));
            }else{
                if($result_set[0]->add_permission == 0){
                    $crud->unset_add();
                }
                if($result_set[0]->edit_permission == 0){
                    $crud->unset_edit();
                }
                if($result_set[0]->delete_permission == 0){
                    $crud->unset_delete();
                }
                if($result_set[0]->print_permission == 0){
                    $crud->unset_print();
                }
                if($result_set[0]->download_permission == 0){
                    $crud->unset_export();
                }
            }
            
            // permission setter

			$crud->columns('menu_setting_id', 'menu_name', 'users_id');
            $crud->unset_fields('create_date','modify_date');
            $crud->required_fields('menu_name','user_id');
            $crud->unique_fields(array('menu_name'));
			
			$users = $this->db->select('user_id, username')->get_where('users', array('blocked' => 0))->result();
			$user_dropdown = array();
			foreach ($users as $user){
				$user_dropdown[$user->user_id] = $user->username;
			}
			$crud->field_type('users_id','multiselect', $user_dropdown);
			$crud->field_type('user_id', 'hidden', $user_id);
			$crud->display_as('users_id', 'Users');

            $output = $crud->render();
            //rending extra value to $output
            $output->tab_title = 'Menu Setting';
            $output->section_heading = 'Menu Setting <small>(Add / Edit / Delete)</small>';
            $output->menu_name = 'Menu Setting';
            $output->add_button = '';

            return array('page'=>'common_v', 'data'=>$output); //loading common view page
        } catch(Exception $e) {
            show_error($e->getMessage().'<br>'.$e->getTraceAsString());
        }
    }

    public function article_master() {
        $data = '';
        return array('page'=>'master/article_master_list_v', 'data'=>$data);
    }

    public function add_article() {
        $this->db->select('ag_id, group_name');
        $data['article_groups'] = $this->db->get_where('article_groups', array('status'=>'1'))->result_array();

        $this->db->select('acc_master.am_id, acc_master.name');
        $this->db->join('acc_groups', 'acc_groups.ag_id = acc_master.ag_id', 'left');
        $this->db->where('acc_groups.group_name', 'Sundry Debtors');
        $this->db->where('acc_master.status', '1');
        $data['customers'] = $this->db->get('acc_master')->result_array(); //Sundry Debtors

        $this->db->select('item_master.im_id, item_master.item');
        $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
        $this->db->where('item_groups.group_name', 'CARTON');
        $this->db->where('item_master.status', '1');
        $data['cartons'] = $this->db->get('item_master')->result_array();

        $data['leather_types'] = array('None','Cow','Buff','Goat','Hair-On','Print');

        return array('page'=>'master/article_master_add_v', 'data'=>$data);
    }

    public function form_add_article() {
        $data_insert['ag_id'] = $this->input->post('ag_id');
        $data_insert['art_no'] = $this->input->post('art_no');
        $data_insert['alt_art_no'] = $this->input->post('alt_art_no');
        $data_insert['info'] = $this->input->post('info');
        $data_insert['design'] = $this->input->post('design');
        $data_insert['pack_dtl'] = $this->input->post('pack_dtl');
        $data_insert['carton_id'] = $this->input->post('carton_id');
		$data_insert['gross_weight_per_carton'] = $this->input->post('gross_weight_per_carton');
		$data_insert['number_of_article_per_carton'] = $this->input->post('number_of_article_per_carton');
        $data_insert['customer_id'] = $this->input->post('customer_id');
        $data_insert['leather_type'] = $this->input->post('leather_type');
        $data_insert['emboss'] = $this->input->post('emboss');
        $data_insert['date'] = $this->input->post('date');
        $data_insert['exworks_amt'] = $this->input->post('exworks_amt');
        $data_insert['cf_amt'] = $this->input->post('cf_amt');
        $data_insert['fob_amt'] = $this->input->post('fob_amt');
        $data_insert['cutting_rate_a'] = $this->input->post('cutting_rate_a');
        $data_insert['cutting_rate_b'] = $this->input->post('cutting_rate_b');
        $data_insert['fabrication_rate_a'] = $this->input->post('fabrication_rate_a');
        $data_insert['fabrication_rate_b'] = $this->input->post('fabrication_rate_b');
        $data_insert['skiving_rate_a'] = $this->input->post('skiving_rate_a');
        $data_insert['skiving_rate_b'] = $this->input->post('skiving_rate_b');
        $data_insert['wl_rate_a'] = $this->input->post('wl_rate_a');
        $data_insert['wl_rate_b'] = $this->input->post('wl_rate_b');
        $data_insert['leather_type_info'] = $this->input->post('leather_type_info');
        $data_insert['metal_fitting'] = $this->input->post('metal_fitting');
        $data_insert['brand'] = $this->input->post('brand');
        $data_insert['hand_machine'] = $this->input->post('hand_machine');
        $data_insert['size'] = $this->input->post('size');
        $data_insert['remark'] = $this->input->post('remark');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;
        //if image uploaded
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/article_img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 1024;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $data_insert['img'] = $uploaded_data['file_name'];
            }
        }

        $this->db->insert('article_master', $data_insert);
        $data['insert_id'] = $this->db->insert_id();

        $data['type'] = 'success';
        $data['msg'] = 'Article added successfully.';
        return $data;
    }

    public function edit_article($am_id) {
        $this->db->select('ag_id, group_name');
        $data['article_groups'] = $this->db->get_where('article_groups', array('status'=>'1'))->result_array();

        $this->db->select('cur_id, currency');
        $data['currencies'] = $this->db->get_where('currencies', array('status'=>'1'))->result_array();

        $this->db->select('acc_master.am_id, acc_master.name');
        $this->db->join('acc_groups', 'acc_groups.ag_id = acc_master.ag_id', 'left');
        $this->db->where('acc_groups.group_name', 'Sundry Debtors');
        $this->db->where('acc_master.status', '1');
        $data['customers'] = $this->db->get('acc_master')->result_array(); //Sundry Debtors

        $this->db->select('item_master.im_id, item_master.item');
        $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
        $this->db->where('item_groups.group_name', 'Carton');
        $this->db->where('item_master.status', '1');
        $data['cartons'] = $this->db->get('item_master')->result_array();

        $data['leather_types'] = array('None','Cow','Buff','Goat','Hair-On','Print');
        $data['colors'] = $this->db->get_where('colors', array('status'=>'1'))->result_array();
        $data['item_groups'] = $this->db->get_where('item_groups', array('status'=>'1'))->result_array();

        $data['article'] = $this->db->get_where('article_master', array('am_id'=>$am_id))->row();

        return array('page'=>'master/article_master_edit_v', 'data'=>$data);
    }

    public function form_edit_article() {
        $article_id = $this->input->post('article_id');
        $data_update['ag_id'] = $this->input->post('ag_id');
        $data_update['art_no'] = $this->input->post('art_no');
        $data_update['alt_art_no'] = $this->input->post('alt_art_no');
        $data_update['info'] = $this->input->post('info');
        $data_update['design'] = $this->input->post('design');
        $data_update['pack_dtl'] = $this->input->post('pack_dtl');
        $data_update['carton_id'] = $this->input->post('carton_id');
		$data_update['gross_weight_per_carton'] = $this->input->post('gross_weight_per_carton');
		$data_update['number_of_article_per_carton'] = $this->input->post('number_of_article_per_carton');		
        $data_update['customer_id'] = $this->input->post('customer_id');
        $data_update['leather_type'] = $this->input->post('leather_type');
        $data_update['emboss'] = $this->input->post('emboss');
        $data_update['date'] = $this->input->post('date');
        $data_update['exworks_amt'] = $this->input->post('exworks_amt');
        $data_update['cf_amt'] = $this->input->post('cf_amt');
        $data_update['fob_amt'] = $this->input->post('fob_amt');
        $data_update['cutting_rate_a'] = $this->input->post('cutting_rate_a');
        $data_update['cutting_rate_b'] = $this->input->post('cutting_rate_b');
        $data_update['fabrication_rate_a'] = $this->input->post('fabrication_rate_a');
        $data_update['fabrication_rate_b'] = $this->input->post('fabrication_rate_b');
        $data_update['skiving_rate_a'] = $this->input->post('skiving_rate_a');
        $data_update['skiving_rate_b'] = $this->input->post('skiving_rate_b');
        $data_update['wl_rate_a'] = $this->input->post('wl_rate_a');
        $data_update['wl_rate_b'] = $this->input->post('wl_rate_b');
        $data_update['leather_type_info'] = $this->input->post('leather_type_info');
        $data_update['metal_fitting'] = $this->input->post('metal_fitting');
        $data_update['brand'] = $this->input->post('brand');
        $data_update['hand_machine'] = $this->input->post('hand_machine');
        $data_update['size'] = $this->input->post('size');
        $data_update['remark'] = $this->input->post('remark');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;
        //if image uploaded
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/article_img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 1024;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $data_update['img'] = $uploaded_data['file_name'];

                //deleting old file from server
                $old_img_name = $this->db->get_where('article_master', array('am_id' => $article_id))->row()->img;
                if ($old_img_name) {
                    $this->load->helper("file");
                    $path = 'assets/admin_panel/img/article_img/' . $old_img_name;
                    unlink($path);
                }
            }
        }

        $this->db->where('am_id', $article_id);
        $this->db->update('article_master', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Article updated successfully.';
        return $data;
    }

    public function form_add_article_color() {
        $data_insert['am_id'] = $this->input->post('article_id');
        $data_insert['lth_color_id'] = $this->input->post('lth_color_id');
        $data_insert['fit_color_id'] = $this->input->post('fit_color_id');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;
        
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/article_img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 1024;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img_color')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $data_insert['img'] = $uploaded_data['file_name'];
            }
        }

        $this->db->insert('article_dtl', $data_insert);

        $data['type'] = 'success';
        $data['msg'] = 'Article color added successfully.';
        return $data;
    }

    public function form_edit_article_color() {
        $article_dtl_id = $this->input->post('article_dtl_id');
        $data_update['lth_color_id'] = $this->input->post('lth_color_id');
        $data_update['fit_color_id'] = $this->input->post('fit_color_id');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;
        
        if (!empty($_FILES)) {
            $config['upload_path'] = 'assets/admin_panel/img/article_img/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|bmp';
            $config['max_size'] = 1024;
            $config['encrypt_name'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('img_color')) { //if file upload unsuccessful
                $data['type'] = 'error';
                $data['title'] = 'Error!';
                $data['msg'] = $this->upload->display_errors();
                return $data;
            } else { //if file upload successful
                $uploaded_data = $this->upload->data();
                $data_update['img'] = $uploaded_data['file_name'];
            }
        }

        $this->db->where('ad_id', $article_dtl_id);
        $this->db->update('article_dtl', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Article color updated successfully.';
        return $data;
    }

    public function ajax_unique_article_no() {
        $article_id = $this->input->post('article_id');
        $art_no = $this->input->post('art_no');
        $rs = $this->db->get_where('article_master', array('am_id !='=>$article_id, 'art_no'=>$art_no))->result();

        if(count($rs) == 0) {
            $data = 'true';
        } else {
            $data = 'Article number already used.';
        }

        return $data;
    }

    public function ajax_unique_alternate_article_no() {
        $article_id = $this->input->post('article_id');
        $alt_art_no = $this->input->post('alt_art_no');
        $rs = $this->db->get_where('article_master', array('am_id !='=>$article_id, 'alt_art_no'=>$alt_art_no))->result();

        if(count($rs) == 0) {
            $data = 'true';
        } else {
            $data = 'Alternate article number already used.';
        }

        return $data;
    }

    public function ajax_unique_article_lth_color() {
        $lth_color_id = $this->input->post('lth_color_id');
        $fit_color_id = $this->input->post('fit_color_id');
        $article_id = $this->input->post('article_id');
        $article_dtl_id = $this->input->post('article_dtl_id');

        $this->db->where('am_id', $article_id);
        $this->db->where('lth_color_id', $lth_color_id);
        $this->db->where('fit_color_id', $fit_color_id);
        $this->db->where('ad_id !=', $article_dtl_id);
        $rs = $this->db->get('article_dtl')->result();

        if(count($rs) == 0) {
            $data = 'true';
        } else {
            $data = 'This article color combination already exists.';
        }

        return $data;
    }

    public function ajax_fetch_article_color() {
        $article_dtl_id = $this->input->post('article_dtl_id');
        $article_dtl_row = $this->db->get_where('article_dtl', array('ad_id'=>$article_dtl_id))->row();

        return $article_dtl_row;
    }

    public function ajax_fetch_article_part() {
        $article_part_id = $this->input->post('article_part_id');
        $article_part_row = $this->db->get_where('article_parts', array('ap_id'=>$article_part_id))->row();

        return $article_part_row;
    }

    public function form_add_article_part() {
        $data_insert['am_id'] = $this->input->post('article_id');
        $data_insert['ig_id'] = $this->input->post('ig_id');
        $data_insert['quantity'] = $this->input->post('quantity');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;

        $this->db->insert('article_parts', $data_insert);

        $data['type'] = 'success';
        $data['msg'] = 'Article part added successfully.';
        return $data;
    }

    public function form_edit_article_part() {
        $article_part_id = $this->input->post('article_part_id');
        $data_update['ig_id'] = $this->input->post('ig_id');
        $data_update['quantity'] = $this->input->post('quantity');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;

        $this->db->where('ap_id', $article_part_id);
        $this->db->update('article_parts', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Article part updated successfully.';
        return $data;
    }

    public function ajax_unique_article_part_item_group() {
        $article_part_id = $this->input->post('article_part_id');
        $article_id = $this->input->post('article_id');
        $ig_id = $this->input->post('ig_id');

        $this->db->where('am_id', $article_id);
        $this->db->where('ig_id', $ig_id);
        $this->db->where('ap_id !=', $article_part_id);
        $rs = $this->db->get('article_parts')->result();

        if(count($rs) == 0) {
            $data = 'true';
        } else {
            $data = 'This item group already exists.';
        }

        return $data;
    }

    public function ajax_item_master_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'group_name',
            1 => 'im_code',
            2 => 'item',
			3 => 'unit',
			4 => 'type',
			5 => 'enlist_costing',
            6 => 'status',
        );
        // Set searchable column fields
        $column_search = array('group_name','im_code','item','type','type');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get('item_master')->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->select('item_master.*, item_groups.group_name, units.unit');
            $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
			$this->db->join('units', 'units.u_id = item_master.u_id', 'left');
            $this->db->limit($limit, $start);
            $this->db->group_by('item_groups.group_name, item_master.im_code');
            $this->db->order_by($order, $dir);
            $rs = $this->db->get('item_master')->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
            $rs = $this->db->get('item_master')->result();
            $totalFiltered = count($rs);

            $this->db->select('item_master.*, item_groups.group_name, units.unit');
            $this->db->join('item_groups', 'item_groups.ig_id = item_master.ig_id', 'left');
			$this->db->join('units', 'units.u_id = item_master.u_id', 'left');
            $this->db->limit($limit, $start);
            $this->db->group_by('item_groups.group_name, item_master.im_code');
            $this->db->order_by($order, $dir);
            $rs = $this->db->get('item_master')->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}
			if($val->enlist_costing == '1'){$exist_in_costing='Yes';} else{$exist_in_costing='No';}
			
            $nestedData['group_name'] = $val->group_name;
            $nestedData['im_code'] = $val->im_code;
            $nestedData['item'] = $val->item;
			$nestedData['item_unit'] = $val->unit;
			$nestedData['item_type'] = $val->type;
			$nestedData['exist_in_costing'] = $exist_in_costing;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="'. base_url('admin/edit_item/'.$val->im_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" pk-name="im_id" pk-value="'.$val->im_id.'" tab="item-master" child="1" ref-table="" ref-pk-name="item-master#multiple-check" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_item_buy_code_table_data(){
        //actual db table column names
        $column_orderable = array(
            0 => 'item_buying_codes.am_id',
            1 => 'item_buying_codes.buyer_im_id',
            2 => 'item_buying_codes.customer_item_code',
            3 => 'item_buying_codes.c_id',
            4 => 'item_buying_codes.customer_colour_code'
        );
        // Set searchable column fields
        $column_search = array('item_buying_codes.am_id', 'item_buying_codes.buyer_im_id','item_buying_codes.customer_item_code','item_buying_codes.c_id','item_buying_codes.customer_colour_code');

        $im_id = $this->input->post('item_id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        //$rs = $this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left')->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();
		
		$this->db->select('item_buying_codes.ibc_id, item_buying_codes.im_id, item_buying_codes.am_id, acc_master.name, item_buying_codes.buying_code,item_buying_codes.buyer_im_id, item_buying_codes.customer_item_code, item_buying_codes.c_id, item_buying_codes.customer_colour_code, im1.item as company_item, im1.im_code as company_im_code, im2.item as buyer_item, im2.im_code as buyer_im_code, colors.color, colors.c_code');
			$this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left');
			$this->db->join('item_master im1', 'im1.im_id = item_buying_codes.im_id', 'left');
			$this->db->join('item_master im2', 'im2.im_id = item_buying_codes.buyer_im_id', 'left');
			$this->db->join('colors', 'colors.c_id = item_buying_codes.c_id', 'left');
			$rs = $this->db->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();
		
        //echo $this->db->last_query();die;
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            //$this->db->select('acc_master.name, item_buying_codes.ibc_id, item_buying_codes.buying_code,item_buying_codes.status');
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            //$rs = $this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left')->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();
			$this->db->select('item_buying_codes.ibc_id, item_buying_codes.im_id, item_buying_codes.am_id, acc_master.name, item_buying_codes.buying_code,item_buying_codes.buyer_im_id, item_buying_codes.customer_item_code, item_buying_codes.c_id, item_buying_codes.customer_colour_code, im1.item as company_item, im1.im_code as company_im_code, im2.item as buyer_item, im2.im_code as buyer_im_code, colors.color, colors.c_code');
			$this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left');
			$this->db->join('item_master im1', 'im1.im_id = item_buying_codes.im_id', 'left');
			$this->db->join('item_master im2', 'im2.im_id = item_buying_codes.buyer_im_id', 'left');
			$this->db->join('colors', 'colors.c_id = item_buying_codes.c_id', 'left');
			$rs = $this->db->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();
			//echo $this->db->last_query();die;
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('item_buying_codes.ibc_id, item_buying_codes.im_id, item_buying_codes.am_id, acc_master.name, item_buying_codes.buying_code,item_buying_codes.buyer_im_id, item_buying_codes.customer_item_code, item_buying_codes.c_id, item_buying_codes.customer_colour_code, im1.item as company_item, im1.im_code as company_im_code, im2.item as buyer_item, im2.im_code as buyer_im_code, colors.color, colors.c_code');
			$this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left');
			$this->db->join('item_master im1', 'im1.im_id = item_buying_codes.im_id', 'left');
			$this->db->join('item_master im2', 'im2.im_id = item_buying_codes.buyer_im_id', 'left');
			$this->db->join('colors', 'colors.c_id = item_buying_codes.c_id', 'left');
			$rs = $this->db->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();
			
			
			$totalFiltered = count($rs);
			

            /*$this->db->select('acc_master.name, item_buying_codes.buying_code');
            $this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left');*/
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            //$totalFiltered = $this->db->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();
			$this->db->select('item_buying_codes.ibc_id, item_buying_codes.im_id, item_buying_codes.am_id, acc_master.name, item_buying_codes.buying_code,item_buying_codes.buyer_im_id, item_buying_codes.customer_item_code, item_buying_codes.c_id, item_buying_codes.customer_colour_code, im1.item as company_item, im1.im_code as company_im_code, im2.item as buyer_item, im2.im_code as buyer_im_code, colors.color, colors.c_code');
			$this->db->join('acc_master', 'acc_master.am_id = item_buying_codes.am_id', 'left');
			$this->db->join('item_master im1', 'im1.im_id = item_buying_codes.im_id', 'left');
			$this->db->join('item_master im2', 'im2.im_id = item_buying_codes.buyer_im_id', 'left');
			$this->db->join('colors', 'colors.c_id = item_buying_codes.c_id', 'left');
			$rs = $this->db->get_where('item_buying_codes', array('item_buying_codes.im_id'=>$im_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            
            //if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['name'] = $val->name;
            $nestedData['buying_code'] = $val->buying_code;
			
			$nestedData['buyer_item_name'] = $val->buyer_item;
			$nestedData['buyer_item_code'] = $val->customer_item_code;
			$nestedData['buyer_item_color'] = $val->color.'['.$val->c_code.']';
			$nestedData['buyer_item_color_code'] = $val->customer_colour_code;
			
            $nestedData['status'] = '';//$status;
            $nestedData['action'] = '
<a href="javascript:void(0)" item_buying_code_id="'.$val->ibc_id.'" class="item_buying_code_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" pk-name="ibc_id" pk-value="'.$val->ibc_id.'" tab="item_buying_codes" child="0" ref-table="" ref-pk-name="" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }
        // print_r($data);die;

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_item_color_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'color',
            2 => 'reorder_qnty',
            4 => 'status',
        );
        // Set searchable column fields
        $column_search = array('color','reorder_qnty');

        $im_id = $this->input->post('item_id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get_where('item_dtl', array('im_id'=>$im_id))->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->select('item_dtl.*, colors.color');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $rs = $this->db->get_where('item_dtl', array('im_id'=>$im_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $rs = $this->db->get_where('item_dtl', array('im_id'=>$im_id))->result();
            $totalFiltered = count($rs);

            $this->db->select('item_dtl.*, colors.color');
            $this->db->join('colors', 'colors.c_id = item_dtl.c_id', 'left');
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $rs = $this->db->get_where('item_dtl', array('im_id'=>$im_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->img){$img = '<img src="'.base_url('assets/admin_panel/img/item_img/'.$val->img).'" width="50">';}else{$img='';}
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['color'] = $val->color;
            $nestedData['opening_stock'] = $val->opening_stock;
            $nestedData['reorder_qnty'] = $val->reorder_qnty;
            $nestedData['img'] = $img;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="javascript:void(0)" item_dtl_id="'.$val->id_id.'" main_color_id="'.$val->c_id.'" class="item_dtl_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" pk-name="id_id" pk-value="'.$val->id_id.'" tab="item-dtl" child="1" ref-table="item-rates" ref-pk-name="id-id" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_item_color_rate_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'name',
            1 => 'purchase_rate',
            2 => 'cost_rate',
            3 => 'gst_percentage',
            4 => 'effective_date',
            5 => 'status',
        );
        // Set searchable column fields
        $column_search = array('name','purchase_rate','cost_rate','gst_percentage','effective_date');

        $item_dtl_id = $this->input->post('item_dtl_id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get_where('item_rates', array('id_id'=>$item_dtl_id))->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('item_rates.*, DATE_FORMAT(item_rates.effective_date, "%d-%m-%Y") as eff_date, acc_master.name');
            $this->db->join('acc_master', 'acc_master.am_id = item_rates.am_id', 'left');
            $rs = $this->db->get_where('item_rates', array('id_id'=>$item_dtl_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('item_rates.*, DATE_FORMAT(item_rates.effective_date, "%d-%m-%Y") as eff_date, acc_master.name');
            $this->db->join('acc_master', 'acc_master.am_id = item_rates.am_id', 'left');
            $rs = $this->db->get_where('item_rates', array('id_id'=>$item_dtl_id))->result();
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('item_rates.*, DATE_FORMAT(item_rates.effective_date, "%d-%m-%Y") as eff_date, acc_master.name');
            $this->db->join('acc_master', 'acc_master.am_id = item_rates.am_id', 'left');
            $rs = $this->db->get_where('item_rates', array('id_id'=>$item_dtl_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['name'] = $val->name;
            $nestedData['purchase_rate'] = $val->purchase_rate;
            $nestedData['cost_rate'] = $val->cost_rate;
            $nestedData['gst_percentage'] = $val->gst_percentage;
            $nestedData['effective_date'] = $val->eff_date;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="javascript:void(0)" item_rate_id="'.$val->ir_id.'" class="item_rate_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" child="0" tab="item-rates" pk-value="'.$val->ir_id.'" pk-name="ir-id" ref-table="" ref-pk-name="" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_fetch_article_rate() {
        $article_rate_id = $this->input->post('article_rate_id');
        $article_rate_row = $this->db->get_where('article_rates', array('ar_id'=>$article_rate_id))->row();

        return $article_rate_row;
    }

    public function form_add_article_rate() {
        $data_insert['am_id'] = $this->input->post('article_id');
        $data_insert['date'] = $this->input->post('date');
        $data_insert['remarks_main'] = $this->input->post('remarks_main');
        $data_insert['cur_id'] = $this->input->post('cur_id');
        $data_insert['currency_rate'] = $this->input->post('currency_rate');
        $data_insert['conversion_rate'] = $this->input->post('conversion_rate');
        $data_insert['exworks_factory'] = $this->input->post('exworks_factory');
        $data_insert['cf_factory'] = $this->input->post('cf_factory');
        $data_insert['fob_factory'] = $this->input->post('fob_factory');
        $data_insert['exworks_office'] = $this->input->post('exworks_office');
        $data_insert['cf_office'] = $this->input->post('cf_office');
        $data_insert['fob_office'] = $this->input->post('fob_office');
        $data_insert['conversion_rate_final'] = $this->input->post('conversion_rate_final');
        $data_insert['exworks_final'] = $this->input->post('exworks_final');
        $data_insert['cf_final'] = $this->input->post('cf_final');
        $data_insert['fob_final'] = $this->input->post('fob_final');
        $data_insert['remarks_final'] = $this->input->post('remarks_final');
        $data_insert['status'] = $this->input->post('status');
        $data_insert['user_id'] = $this->session->user_id;

        $this->db->insert('article_rates', $data_insert);

        $data['type'] = 'success';
        $data['msg'] = 'Article rate added successfully.';
        return $data;
    }

    public function form_edit_article_rate() {
        $article_rate_id = $this->input->post('article_rate_id');
        $data_update['date'] = $this->input->post('date');
        $data_update['remarks_main'] = $this->input->post('remarks_main');
        $data_update['cur_id'] = $this->input->post('cur_id');
        $data_update['currency_rate'] = $this->input->post('currency_rate');
        $data_update['conversion_rate'] = $this->input->post('conversion_rate');
        $data_update['exworks_factory'] = $this->input->post('exworks_factory');
        $data_update['cf_factory'] = $this->input->post('cf_factory');
        $data_update['fob_factory'] = $this->input->post('fob_factory');
        $data_update['exworks_office'] = $this->input->post('exworks_office');
        $data_update['cf_office'] = $this->input->post('cf_office');
        $data_update['fob_office'] = $this->input->post('fob_office');
        $data_update['conversion_rate_final'] = $this->input->post('conversion_rate_final');
        $data_update['exworks_final'] = $this->input->post('exworks_final');
        $data_update['cf_final'] = $this->input->post('cf_final');
        $data_update['fob_final'] = $this->input->post('fob_final');
        $data_update['remarks_final'] = $this->input->post('remarks_final');
        $data_update['status'] = $this->input->post('status');
        $data_update['user_id'] = $this->session->user_id;

        $this->db->where('ar_id', $article_rate_id);
        $this->db->update('article_rates', $data_update);

        $data['type'] = 'success';
        $data['msg'] = 'Article rate updated successfully.';
        return $data;
    }

    public function ajax_unique_article_rate_date() {
        $date = $this->input->post('date');
        $article_id = $this->input->post('article_id');
        $article_rate_id = $this->input->post('article_rate_id');

        $this->db->where('am_id', $article_id);
        $this->db->where('date', $date);
        $this->db->where('ar_id !=', $article_rate_id);
        $rs = $this->db->get('article_rates')->result();

        if(count($rs) == 0) {
            $data = 'true';
        } else {
            $data = 'Rate for this date already exists.';
        }

        return $data;
    }

    public function ajax_article_rate_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'date',
            2 => 'status',
        );
        // Set searchable column fields
        $column_search = array('date','remarks_main');

        $article_id = $this->input->post('article_id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->select("*, DATE_FORMAT(date, '%d-%m-%Y') as date")->get_where('article_rates', array('am_id'=>$article_id))->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $rs = $this->db->select("*, DATE_FORMAT(date, '%d-%m-%Y') as date")->get_where('article_rates', array('am_id'=>$article_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $rs = $this->db->select("*, DATE_FORMAT(date, '%d-%m-%Y') as date")->get_where('article_rates', array('am_id'=>$article_id))->result();
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $rs = $this->db->select("*, DATE_FORMAT(date, '%d-%m-%Y') as date")->get_where('article_rates', array('am_id'=>$article_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['date'] = $val->date;
            $nestedData['remarks_main'] = $val->remarks_main;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="javascript:void(0)" article_rate_id="'.$val->ar_id.'" class="article_rate_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" tab="article-rates" pk-name="ar-id" pk-value="'.$val->ar_id.'" child="0" ref-table="" ref-pk-name="" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_article_part_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'group_name',
            1 => 'quantity',
            2 => 'status',
        );
        // Set searchable column fields
        $column_search = array('group_name','quantity');

        $article_id = $this->input->post('article_id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get_where('article_parts', array('am_id'=>$article_id))->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('article_parts.*, item_groups.group_name');
            $this->db->join('item_groups', 'item_groups.ig_id = article_parts.ig_id', 'left');
            $rs = $this->db->get_where('article_parts', array('am_id'=>$article_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $rs = $this->db->get_where('article_parts', array('am_id'=>$article_id))->result();
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('article_parts.*, item_groups.group_name');
            $this->db->join('item_groups', 'item_groups.ig_id = article_parts.ig_id', 'left');
            $rs = $this->db->get_where('article_parts', array('am_id'=>$article_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['group_name'] = $val->group_name;
            $nestedData['quantity'] = $val->quantity;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="javascript:void(0)" article_part_id="'.$val->ap_id.'" class="article_part_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
            ';
            // <a href="javascript:void(0)" class="btn btn-danger"><i class="fa fa-times"></i> Delete</a>
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_article_color_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'lth_color',
            1 => 'fit_color',
            2 => 'img',
            3 => 'status',
        );
        // Set searchable column fields
        $column_search = array('lth_color','fit_color');

        $article_id = $this->input->post('article_id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get_where('article_dtl', array('am_id'=>$article_id))->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('article_dtl.*, colors_1.color as lth_color, colors_2.color as fit_color');
            $this->db->join('colors colors_1', 'colors_1.c_id = article_dtl.lth_color_id', 'left');
            $this->db->join('colors colors_2', 'colors_2.c_id = article_dtl.fit_color_id', 'left');
            $rs = $this->db->get_where('article_dtl', array('am_id'=>$article_id))->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('article_dtl.*, colors_1.color as lth_color, colors_2.color as fit_color');
            $this->db->join('colors colors_1', 'colors_1.c_id = article_dtl.lth_color_id', 'left');
            $this->db->join('colors colors_2', 'colors_2.c_id = article_dtl.fit_color_id', 'left');
            $rs = $this->db->get_where('article_dtl', array('am_id'=>$article_id))->result();
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('article_dtl.*, colors_1.color as lth_color, colors_2.color as fit_color');
            $this->db->join('colors colors_1', 'colors_1.c_id = article_dtl.lth_color_id', 'left');
            $this->db->join('colors colors_2', 'colors_2.c_id = article_dtl.fit_color_id', 'left');
            $rs = $this->db->get_where('article_dtl', array('am_id'=>$article_id))->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['lth_color'] = $val->lth_color;
            $nestedData['fit_color'] = $val->fit_color;
            $nestedData['img'] = ($val->img != null) ? '<img src="' . base_url() . 'assets/admin_panel/img/article_img/' . $val->img . '" />' : 'No image provided';
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="javascript:void(0)" article_dtl_id="'.$val->ad_id.'" class="article_dtl_edit_btn btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" pk-name="ad-id" pk-value="'.$val->ad_id.'" tab="article-dtl" child="1" ref-table="customer-order-dtl" ref-pk-name="art-master#multiple-check" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_article_master_table_data() {
        //actual db table column names
        $column_orderable = array(
            0 => 'group_name',
            1 => 'art_no',
            2 => 'info',
            3 => 'name',
            5 => 'item',
            6 => 'exworks_amt',
            7 => 'cf_amt',
            8 => 'fob_amt',
            9 => 'status'
        );
        // Set searchable column fields
        $column_search = array('group_name','art_no','info','name','item');

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $column_orderable[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];
        $search = $this->input->post('search')['value'];

        $rs = $this->db->get('article_master')->result();
        $totalData = count($rs);
        $totalFiltered = $totalData;

        //if not searching for anything
        if(empty($search)) {
            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('article_master.*, article_groups.group_name, acc_master.name, item_master.item');
            $this->db->join('article_groups', 'article_groups.ag_id = article_master.ag_id', 'left');
            $this->db->join('acc_master', 'acc_master.am_id = article_master.customer_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = article_master.carton_id', 'left');
            $rs = $this->db->get('article_master')->result();
        }
        //if searching for something
        else {
            $this->db->start_cache();
            // loop searchable columns
            $i = 0;
            foreach($column_search as $item){
                // first loop
                if($i===0){
                    $this->db->group_start(); //open bracket
                    $this->db->like($item, $search);
                }else{
                    $this->db->or_like($item, $search);
                }
                // last loop
                if(count($column_search) - 1 == $i){
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }
            $this->db->stop_cache();

            $this->db->select('article_master.*, article_groups.group_name, acc_master.name, item_master.item');
            $this->db->join('article_groups', 'article_groups.ag_id = article_master.ag_id', 'left');
            $this->db->join('acc_master', 'acc_master.am_id = article_master.customer_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = article_master.carton_id', 'left');
            $rs = $this->db->get('article_master')->result();
            $totalFiltered = count($rs);

            $this->db->limit($limit, $start);
            $this->db->order_by($order, $dir);
            $this->db->select('article_master.*, article_groups.group_name, acc_master.name, item_master.item');
            $this->db->join('article_groups', 'article_groups.ag_id = article_master.ag_id', 'left');
            $this->db->join('acc_master', 'acc_master.am_id = article_master.customer_id', 'left');
            $this->db->join('item_master', 'item_master.im_id = article_master.carton_id', 'left');
            $rs = $this->db->get('article_master')->result();

            $this->db->flush_cache();
        }

        $data = array();
        foreach ($rs as $val) {
            if($val->img){$img='<img src="'.base_url('assets/admin_panel/img/article_img/'.$val->img).'" width="50">';} else{$img='';}
            if($val->status == '1'){$status='Enable';} else{$status='Disable';}

            $nestedData['group_name'] = $val->group_name;
            $nestedData['art_no'] = $val->art_no;
            $nestedData['info'] = $val->info;
            $nestedData['name'] = $val->name;
            $nestedData['date'] = $val->date;
            $nestedData['item'] = $val->item;
            $nestedData['img'] = $img;
            $nestedData['fabrication_rate_b'] = $val->fabrication_rate_b;
            $nestedData['exworks_amt'] = $val->exworks_amt;
            $nestedData['fob_amt'] = $val->fob_amt;
            $nestedData['cf_amt'] = $val->cf_amt;
            $nestedData['status'] = $status;
            $nestedData['action'] = '
<a href="'. base_url('admin/edit_article/'.$val->am_id) .'" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
<a href="javascript:void(0)" child="1" tab="article-master" pk-value="'.$val->am_id.'" pk-name="am-id" ref-table="article-costing" ref-pk-name="am-id" class="btn btn-danger delete"><i class="fa fa-times"></i> Delete</a>
            ';
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        return $json_data;
    }

    public function ajax_del_item_master_color(){
        $color_id = $this->input->post('pk_value');
        $nr1 = $this->db->get_where('item_rates', array('id_id' => $color_id))->num_rows();
        
        // *********************** - MORE CHECKING NEEDED - ***********************

        if($nr1 > 0){
            $warning = 1;
        }else{
            $warning = 0;
        }

        if($warning == 1){
            $data['type'] = 'warning';
            $data['msg'] = 'Unsuccessful! Item already exists in another table'; 
        }else{
            $data = $this->log_and_direct_delete('id_id', $color_id, 'item_dtl');
        }
        return $data;        
    }

    public function ajax_del_item_master(){

        $item_id = $this->input->post('pk_value');
        $nr1 = $this->db->get_where('item_dtl', array('im_id' => $item_id))->result();
        $nr2 = $this->db->get_where('item_rates', array('id_id' => $nr1[0]->id_id))->num_rows();
        if(count($nr1) > 0 or $nr2 > 0){
            $warning = 1;
        }else{
            $warning = 0;
        }

        if($warning == 1){
            $data['type'] = 'warning';
            $data['msg'] = 'Unsuccessful! Item already exists in another table'; 
        }else{
            $data = $this->log_and_direct_delete('im_id', $item_id, 'item_master');
        }
        return $data;    
    }

    public function ajax_del_article_master(){

        $article_id = $this->input->post('pk_value');
        $nr1 = $this->db->get_where('article_dtl', array('am_id' => $article_id))->result();
        $nr2 = $this->db->get_where('article_costing', array('am_id' => $article_id))->num_rows();
        $nr3 = $this->db->get_where('customer_order_dtl', array('am_id' => $article_id))->num_rows();
        
        // *********************** - MORE CHECKING NEEDED - ***********************
        
        if(count($nr1) > 0 or $nr2 > 0 or $nr3 > 0){
            $warning = 1;
        }else{
            $warning = 0;
        }

        if($warning == 1){
            $data['type'] = 'warning';
            $data['msg'] = 'Unsuccessful! Item already exists in another table'; 
        }else{
            $data = $this->log_and_direct_delete('am_id', $article_id, 'article_master');
        }
        return $data;    
    }

    public function ajax_del_row_on_table_and_pk(){
        $warning = 0;
        $pk_name = str_replace('-','_',$this->input->post('pk_name'));
        $pk_value = $this->input->post('pk_value');
        $table = str_replace('-','_',$this->input->post('tab'));
        $child = $this->input->post('child');
        $ref_table = str_replace('-','_',$this->input->post('ref_table'));
        $ref_pk_name = str_replace('-','_',$this->input->post('ref_pk_name')); 

        // checking article details table with customer order 
        if($this->input->post('ref_pk_name') == "art-master#multiple-check"){
            
            $details = $this->db->get_where($table, array($pk_name => $pk_value))->result();
            $nr = $this->db->get_where($ref_table, array('am_id' => $details[0]->am_id, 'fc_id' => $details[0]->fit_color_id, 'lc_id' => $details[0]->lth_color_id))->num_rows();
            if($nr > 0){
                $warning = 1;
            }else{
                $warning = 0;
            }
            // echo '<pre>',print_r($details), '</pre>';die;
        }else { # all other master delete 
            if($child == 0){
                $warning = 0;
            }else{
                $nr = $this->db->get_where($ref_table, array($ref_pk_name => $pk_value))->num_rows();
                
                if($nr > 0){
                    $warning = 1;
                }else{
                    $warning = 0;   
                }
            }
        }

        if($warning == 1){
            $data['type'] = 'warning';
            $data['msg'] = 'Unsuccessful! Item already exists in another table'; 
        }else{
            $data = $this->log_and_direct_delete($pk_name, $pk_value, $table);
        }
        
        return $data;
    }

    private function log_and_direct_delete($pk_name, $pk_value, $table){
        // log data first 
        
        $user_data = $this->db->where($pk_name, $pk_value)->get($table)->row();
        $insertArray = array(
            'table_name' => $table,
            'pk_id' => $pk_value,
            'action_taken'=>'delete', 
            'old_data' => json_encode($user_data),
            'user_id' => $this->session->user_id,
            'comment' => 'master'
        );
        if($this->db->insert('user_logs', $insertArray)){

            $this->db->where($pk_name, $pk_value)->delete($table);
            $data['title'] = 'Deleted!';
            $data['type'] = 'success';
            $data['msg'] = 'Item Successfully Deleted';
            
        }else{
            return false;
        }

        return $data;
    }

}