<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basic_search_controller extends CI_Controller {

	
	function search()
	{

		$this->load->model('Basic_search_model');
		$data['query'] = $this->Basic_search_model->get_search();
		$this->load->view('basicSearch_view', $data);
		
	}
}

