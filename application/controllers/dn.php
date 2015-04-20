<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dn extends MY_Controller
{
    function __construct() {
        parent::__construct("authorize", "dn", true);
        $this->load->model('dn_model');
        $this->load->model('so_model');
        $this->load->model('stock_transaction_model');
          
    }
    public function get_dn_list()
    {
    
    echo "{\"data\" : " . json_encode($this->dn_model->get_dn_all()) . "}";
    }
    
    public function get_dn_list_open()
    {
    
    echo "{\"data\" : " . json_encode($this->dn_model->get_dn_open()) . "}";
    }
    
    public function get_dn_list_close()
    {
    
    echo "{\"data\" : " . json_encode($this->dn_model->get_dn_close()) . "}";
    }
    
    public function init_dn_product()
    {
        $data = array(
            "so" => $this->so_model->get_so_all(),
            "dn" => $this->dn_model->get_dn_all(),
            
        );
        
        return $data;
    }
    
    public function init_create_dn()
    {
        $data = array();
        if($this->input->post('id_so'))
        {
            $data['from_so'] = 'true';
            $data['so'] = $this->so_model->get_so_by_id($this->input->post('id_so'));
        }
        
        return $data;
    }
     
    public function save_dn()
    {
        $id_dn = null;
        if($this->input->post('is_edit') == 'false')
        {
            $id_dn = $this->dn_model->save_dn($this->input->post());
        }
        else
        {
            $this->dn_model->edit_dn($this->input->post());
            $id_dn = $this->input->post('id_dn');
        }
        
        $interfunction_param[0] = array("paramKey" => "id", "paramValue" => $id_dn);
        return array("interfunction_param" => $interfunction_param);
    }
    
    public function delete_dn()
    {
        $dataDn = $this->dn_model->get_dn_by_id($id);
        
        if($dataDn[0]['status'] != 'close')
        {
            $this->dn_model->change_dn_status($this->input->post('id_dn'), 'void');
            if($dataDn[0]['status'] != 'draft')
            {
                $dn = $this->dn_model->get_dn_by_id($id);
                $data = $this->dn_model->get_dn_product_by_id_dn($id);
                $this->stock_transaction_model->automatic_stock_transaction('void ' + $dn[0]['dn_number'], 'delivery_note', date('Y-m-d H:i:s'), $this->dn_model->get_virtual_location(), $data['product']);
                $this->so_model->change_so_status($data['so'], 'open');
            }
        }
    }
    
    public function init_edit_dn($id)
    {
        $data = array(
            "so" => $this->so_model->get_so_all(),
            "dn" => $this->dn_model->get_dn_all(),
            "data_edit" => $this->dn_model->get_dn_by_id($id),
            "is_edit" => 'true'
        );
        
        return $data;   
    }
    
    public function view_dn_detail($id)
    {
        $data = array(
            "so" => $this->so_model->get_so_all(),
            "dn" => $this->dn_model->get_dn_all(),
            "data_edit" => $this->dn_model->get_dn_by_id($id),
            "is_edit" => 'true',
            "is_view" => 'true'
        );
        
        return $data;   
    }
    
    public function get_dn_product_list()
    {
        echo "{\"data\" : " . json_encode($this->dn_model->get_dn_product_by_id_dn($this->input->get('id'))) . "}";
        
    }
    
    public function validate_dn($id)
    {
        //$param = null;
        //$param = $this->dn_model->validate_dn($id);
        //$dn = $this->dn_model->get_dn_by_id($id);
        //$interfunction_param = array();
        //$interfunction_param[0] = array("paramKey" => "id", "paramValue" => $id);
        //return array('log_param' => $param, "interfunction_param" => $interfunction_param);
        $data = $this->input->post();
        $dn = $this->dn_model->get_dn_by_id($id);
        $this->stock_transaction_model->automatic_stock_transaction_out($dn[0]['no_dn'], 'delivery_note', date('Y-m-d H:i:s'), $this->dn_model->get_virtual_location(), $data['products']);
        $this->dn_model->change_dn_status($id, 'open');
        $this->so_model->change_so_status($data['so'], 'deliver');
    }
    
    public function return_dn($id)
    {
        $this->dn_model->change_dn_status($id, 'close');
    }
    
}
?>
