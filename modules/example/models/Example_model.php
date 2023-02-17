<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Example_model extends CI_Model
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
        return $this->db->get_where("example", array("id" => $id, "deleted_at" => null))->row(0);
    }

    /**
     * Read all data.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->db->get_where("example", array("deleted_at" => null))->result();
    }

    /**
     * Insert data.
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->db->insert('example', $data);
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
        $this->db->update('example', $data);
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
