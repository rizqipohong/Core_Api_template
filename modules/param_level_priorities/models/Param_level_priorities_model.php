<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Param_level_priorities_model extends CI_Model
{
    /**
     * Role constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function find($id)
    {
        return $this->db->get_where("tb_param_level_priorities", array("id_level" => $id))->row(0);
    }

    public function all($filters = array())
    {
        if (empty($filters))
            return $this->db->get_where("tb_param_level_priorities")->result_array();
        else {
            return $this->db->get_where("tb_param_level_priorities", $filters)->row_array();
        }
    }
}
