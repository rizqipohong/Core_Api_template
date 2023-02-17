<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    /**
     * Role constructor.
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
    public function find($id)
    {
        return $this->db->get_where("product", array("id" => $id, "deleted_at" => null))->row(0);
    }

    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all($filters = array())
    {
        if (empty($filters))
            $data = $this->db->get_where("product", array("deleted_at" => null))->result();
        else {
            $data = $this->db->select("*")->from("product")->where("deleted_at", null);
            if (isset($filters['name']) && $filters['name'] != '') $data = $data->like('name', $filters['name']);
            if (isset($filters['description']) && $filters['description'] != '') $data = $data->where('description', $filters['description']);
            if ((isset($filters['page']) && $filters['page'] != '') && (isset($filters['row_per_page']) && $filters['row_per_page'] != '')) $data = $data->limit($filters['row_per_page'], $filters['page']);
            $data = $data->get()->result_array();
        }
        return $data;
    }

    /**
     * Insert data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->db->insert('product', $data);
    }

    /**
     * Edit data.
     *
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        if (!$this->find($id)) {
            $this->validation_lib->respondError('ID tidak ditemukan!');
        }
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('product', $data);
        $this->db->trans_complete();
        //anticipate if row doesn't have any data change, it will return num_rows 0. Let's check if query doesn't have error return true
        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        }
        return true;
    }

    /**
     * Delete data.
     *
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $data['deleted_at'] = date("Y-m-d H:i:s");
        return $this->find($id) ? $this->edit($id, $data) : $this->validation_lib->respondError('ID tidak ditemukan!');;
    }
}
