<?php

class Admin_controller extends CI_Controller

{
		
		
	function index(){
		
		$this->load->view('header_view', $data);
		$this->load->view('admin_view', $data);
		$this->load->view('footer_view', $data);
		
		
	}
	
	
	
	
}




?>