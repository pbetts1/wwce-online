<?php

class Admin_model extends Model{
		
		
	
	public function get_records()
	{
			
				
				$this->db->select('*');
				$this->db->from('synopsis_header');
				$this->db->join('research_study', 'synopsis_header.ProgramID = research_study.ProgramID');
	
				$query = $this->db->get();
				
				return $query->result();      
		
	}
	
	
	function add_record($data) {
		
		$this->db->insert('data', $data);
		
	}
		
	
	function update_record() {
		
		$this->db->where('ProgramID', 14);
		$this->db->update('data',$data);
		
	}	
	
    function delete_row()
	{
		$this->db->where('ProgramID', $this->uri->segment(3));
		
	}

	
	
	
	
}




?>