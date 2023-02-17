<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_tier_model extends CI_Model
{
    /**
     * Tipe_industri_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Find data.
     *
     * @param $id
     * @return mixed
     */
    public function find($filters)
    {
        return $this->db->get_where("tb_master_tier", $filters)->row(0);
    }

    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get("tb_master_tier")->result();
    }
}