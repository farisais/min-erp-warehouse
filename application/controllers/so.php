<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class So extends MY_Controller
{
    function __construct() {
        parent::__construct("authorize", "so", true);
        $this->load->model('so_model');
          
    }
    public function get_so_list()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_so_all()) . "}";
    }
    
    public function get_so_idr_list()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_so_idr_all()) . "}";
    }
    
    public function save_so()
    {
        if($this->input->post('is_edit') == 'true')
        {
            $this->so_model->edit_so($this->input->post());
        }
        else
        {
            $this->so_model->add_so($this->input->post());
        }
        
        return null;
        
    }
     public function delete_so()
    {
        $this->so_model->change_so_status($this->input->post('id_so'), 'void');
    }
    
    public function validate_so($id)
    {
        $param = null;
        $param = $this->so_model->validate_so($id);
        $interfunction_param = array();
        $interfunction_param[0] = array("paramKey" => "id", "paramValue" => $id);
        return array('log_param' => $param, "interfunction_param" => $interfunction_param);
    }
    
    public function init_edit_so($id)
    {
        $data = array(
            "data_edit" => $this->so_model->get_so_by_id($id),
            "is_edit" => 'true'
        );
        
        return $data;
    }
    
    public function view_so_detail($id)
    {
        $data = array(
            "data_edit" => $this->so_model->get_so_by_id($id),
            "is_edit" => 'true',
            "is_view" => 'true'
        );
        
        return $data;
    }
    
    public function get_so_open_list()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_so_open()) . "}";
    }
    
    public function get_so_deliver_list()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_so_deliver()) . "}";
    }
    
    public function get_so_product_list()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_so_product_by_id($this->input->get('id'))) . "}";
    }
    
    public function get_so_product_open_dn()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_so_product_open_dn($this->input->get('id'))) . "}";
    }
    
    public function init_view_so_cost()
    {
         return null;
    }
    
    public function get_pl_product_from_so()
    {
        echo "{\"data\" : " . json_encode($this->so_model->get_pl_product_from_so($this->input->get('id'))) . "}";
    }
}
?>
