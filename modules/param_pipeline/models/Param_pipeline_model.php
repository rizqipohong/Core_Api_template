<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Param_pipeline_model extends CI_Model
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
        return $this->db->get_where("tb_param_pipeline", array("id_pipeline" => $id, "is_delete" => "No"))->row(0);
    }

    public function all($filters = array())
    {

        if (empty($filters))
            $data = $this->db->select("a.*, b.level_name as level_priorities, c.level_name as level_risk, d.tier_name,,
           ( CASE
            WHEN (select count(sub_a.plan_id) from tb_plan_disbursement sub_a where a.id_pipeline = sub_a.source_id) > 0 THEN 0
            ELSE 1
          END) as deletable,
            ")
                ->from("tb_param_pipeline a")
                ->join("tb_param_level_priorities b", "b.id_level = a.level_of_priorities", "left")
                ->join("tb_param_level_risk c", "c.id_level = a.level_of_risk", "left")
                ->join("tb_master_tier d", "d.id_tier = a.tier", "left")
                ->where("a.is_delete", "No")
                ->order_by('a.id_pipeline', 'ASC')->get()->result_array();
        else {

            $data = $this->db->select("a.*, b.level_name as level_priorities, c.level_name as level_risk, d.tier_name,
           ( CASE
            WHEN (select count(sub_a.plan_id) from tb_plan_disbursement sub_a where a.id_pipeline = sub_a.source_id) > 0 THEN 0 ELSE 1
            END
            )as deletable,")
                ->from("tb_param_pipeline a")
                ->join("tb_param_level_priorities b", "b.id_level = a.level_of_priorities", "left")
                ->join("tb_param_level_risk c", "c.id_level = a.level_of_risk", "left")
                ->join("tb_master_tier d", "d.id_tier = a.tier", "left")
                ->where("a.is_delete", "No");
            if (isset($filters['pipeline_origin']) && $filters['pipeline_origin'] != '') $data = $data->like('a.pipeline_origin', $filters['pipeline_origin']);
            if (isset($filters['level_of_priorities']) && $filters['level_of_priorities'] != '') $data = $data->where('a.level_of_priorities', $filters['level_of_priorities']);
            if (isset($filters['level_of_risk']) && $filters['level_of_risk'] != '') $data = $data->where('a.level_of_risk', $filters['level_of_risk']);
            if (isset($filters['tier']) && $filters['tier'] != '') $data = $data->where('a.tier', $filters['tier']);
            if ((isset($filters['page']) && $filters['page'] != '') && (isset($filters['row_per_page']) && $filters['row_per_page'] != '')) $data = $data->limit($filters['row_per_page'], $filters['page']);
            $data = $data->order_by('a.created_at', 'ASC')->get()->result_array();
        }
        return $data;
    }

    public function add($data)
    {
        return $this->db->insert('tb_param_pipeline', $data);
    }

    public function edit($id, $data)
    {
        if (!$this->find($id)) {
            $this->validation_lib->respondError('ID tidak ditemukan!');
        }
        $this->db->trans_start();
        $this->db->where('id_pipeline', $id);
        $this->db->update('tb_param_pipeline', $data);
        $this->db->trans_complete();
        //anticipate if row doesn't have any data change, it will return num_rows 0. Let's check if query doesn't have error return true
        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        }
        return true;
    }


    public function delete($id)
    {
        $data['deleted_at'] = date("Y-m-d H:i:s");
        $data['is_delete'] = "Yes";
        return $this->find($id) ? $this->edit($id, $data) : $this->validation_lib->respondError('ID tidak ditemukan!');;
    }
}
