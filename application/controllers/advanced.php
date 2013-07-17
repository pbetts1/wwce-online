<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advanced extends CI_Controller {

	 function __construct()
    {
        parent::__construct();
    }
	
	
	public function index()
	{
		$this->load->view('header_view');
		$this->load->view('advanced_view');
		$this->load->view('footer_view');
	}
	
	
	public function search()
	{

		$this->load->model('Advanced_search_model');
		$data['query'] = $this->Advanced_search_model->get_search();
		$this->load->view('header_view');
		$this->load->view('basicSearch_view', $data);
		$this->load->view('footer_view');
		
	}
	
	
	public function results()
	{
        
		$this->load->model('Get_study_model');
		$data['query'] = $this->Get_study_model->get_study();
		$this->load->view('header_view', $data);
		$this->load->view('results_view', $data);
		$this->load->view('footer_view', $data);
		
	}
	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */