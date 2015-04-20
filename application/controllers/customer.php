<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends MY_Controller
{
    function __construct() {
        parent::__construct("authorize", "customer", true);
        $this->load->model('customer_model');
          
    }
    public function get_customer_list()
    {
        echo "{\"data\" : " . json_encode($this->customer_model->get_customer_all()) . "}";
    }
    
     public function save_customer()
    {
        if($this->input->post('is_edit') == 'true')
        {
            $this->customer_model->edit_customer($this->input->post());
        }
        else
        {
            $this->customer_model->add_customer($this->input->post());
        }
        
        return null;
    }
    
    public function delete_customer()
    {
        $this->customer_model->delete_customer($this->input->post('id_customer'));
        
        return null;
    }
    
    public function init_edit_customer($id)
    {
        $data = array(
            'data_edit' => $this->customer_model->get_customer_by_id($id),
            'is_edit' => true
        );
        
        return $data;
    }
    
    public function view_customer_detail($id)
    {
        $data = array(
            'data_edit' => $this->customer_model->get_customer_by_id($id),
            'is_edit' => true,
            'is_view' => true
        );
        
        return $data;
    }
}
?>
