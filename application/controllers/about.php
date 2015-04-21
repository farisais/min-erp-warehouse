<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class About extends MY_Controller
{
	public function __construct()
	{
		parent::__construct('authorize', 'about');
 
	}
	
	public function index()
	{
		$this->data['title'] = 'Desalite | About';
		$this->data['subtitle'] = 'About Desalite Backoffice Web V.1.0';
		//$this->data['content'] = 'Desalite Web V.1.0';
                //$this->data['content'] = $this->load->view('about');
                        
		$this->template->load('default', 'about/index', $this->data);
	}
}
?>