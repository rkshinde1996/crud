<?php
defined('BASEPATH') OR exit('Some thing error occured while M_CR');


class Crud extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }


    public function commonCheck($cols, $table, $wherecondition)
    {
        $count = $this->db->select($cols)->from($table)->where($wherecondition)->get()->num_rows();
        return ($count > 0) ? 1 : 0;
    }


    public function commonInsert($table, $insertdata) {

	 	$this->db->insert($table,$insertdata);

	 	if($this->db->affected_rows() != 1){
	 		$response = array('code' => 0, 'description' => 'Error while saving data.');
	 	}else{
	 		$response = array('code' => 1, 'description' => 'Data saved successfully.', 'insert_id' => $this->db->insert_id());
	 	}
 		return json_encode($response);
    }


    public function commonUpdate($table, $data, $where) {

        $result = $this->db->where($where)->update($table, $data);

        if($result) {
	 		$response = array('code' => 1, 'description' => 'Data updated successfully.');
	 	}else{
	 		$response = array('code' => 0, 'description' => 'Date not updated.');
	 	}
 		return json_encode($response);
    }


    public function commonget($params){

        $where=(isset($params['where']))?$params['where']:array();

        $this->db->select('*');
    	$this->db->from($params['table']);
		$this->db->where($where);  
		$res = $this->db->get();
		$count = $res->num_rows();

		if ($count > 0) {
	 		$response = array('code' => 1, 'description' => 'Data fetched successfully.', 'result' => $res->result(), 'row' => $res->row());
	 	}else{
			$response = array('code' => 0, 'description' => 'Error while fetching data.');
	 	}
 		return json_encode($response);
    }

}