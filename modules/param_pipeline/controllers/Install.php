<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class Example
 *
 * @property Authentication authentication
 * @property Validation_lib validation_lib
 */
class Install extends CI_Controller
{

    public function index()
    {
        date_default_timezone_set("Asia/Jakarta");
        if (!$this->db->table_exists('tb_param_pipeline')) {
            $tableQuery = "CREATE TABLE `tb_param_pipeline` (
              `id_pipeline` int(11) NOT NULL AUTO_INCREMENT,
              `pipeline_origin` varchar(200) NOT NULL,
              `level_of_priorities` int(11) NOT NULL,
              `level_of_risk` int(11) NOT NULL,
              `tier` int(11) NOT NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `deleted_at` datetime DEFAULT NULL,
              `is_delete` enum('No','Yes') NOT NULL DEFAULT 'No',
              PRIMARY KEY (`id_pipeline`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            $proses = $this->db->query($tableQuery);
            if (!$proses) {
                $this->validation_lib->respondError('Gagal menambahkan tabel tb_param_pipeline');
            }
        }

        $this->db->empty_table('tb_param_pipeline');
        $data = [
            [
                'id_pipeline' => 1,
                'pipeline_origin' => 'Ekosistem',
                'level_of_priorities' => '1',
                'level_of_risk' => '3',
                'tier' => '1',
            ],
            [
                'id_pipeline' => 2,
                'pipeline_origin' => 'Existing Payor',
                'level_of_priorities' => '1',
                'level_of_risk' => '3',
                'tier' => '1',
            ],

            [
                'id_pipeline' => 3,
                'pipeline_origin' => 'Existing Lender Bank',
                'level_of_priorities' => '2',
                'level_of_risk' => '3',
                'tier' => '2',
            ],
            [
                'id_pipeline' => 4,
                'pipeline_origin' => 'Existing Borrower',
                'level_of_priorities' => '2',
                'level_of_risk' => '2',
                'tier' => '2',
            ],
            [
                'id_pipeline' => 5,
                'pipeline_origin' => 'Agent',
                'level_of_priorities' => '3',
                'level_of_risk' => '1',
                'tier' => '3',
            ],
        ];
        foreach ($data as $key => $value) {
            $check = $this->db->get_where('tb_param_pipeline', ['id_pipeline' => $value['id_pipeline']])->row();
            if (empty($check)) {
                $this->db->insert('tb_param_pipeline', $value);
            } else {
                $this->db->update('tb_param_pipeline', ['pipline_origin' => $value['pipline_origin']]);
            }
        }
        //PLAN DISBURSEMENT
        if (!$this->db->table_exists('tb_plan_disbursement')) {
            //        bikin query untuk create table - TO DO dan alter table
            $queryCreateTable = "CREATE TABLE `tb_plan_disbursement` (
              `plan_id` int(11) NOT NULL AUTO_INCREMENT,
              `register_code` varchar(50) DEFAULT NULL,
              `potential_borrower_name` varchar(200) DEFAULT NULL,
              `payor_code` varchar(200) DEFAULT NULL,
              `source_id` int(11) DEFAULT NULL,
              `agent_id` varchar(30) DEFAULT NULL,
              `id_backend_users` int(10) DEFAULT NULL,
              `plan_potency` decimal(30) DEFAULT NULL,
              `id_param_business_field` int(11) DEFAULT NULL,
              `actual_plan` int(20) DEFAULT NULL,
              `company_since` date DEFAULT NULL,
              `cooperation_since` date DEFAULT NULL,
              `committee_generate_date` date DEFAULT NULL,
              `committee_cancel_status` enum('Process','Cancel') DEFAULT NULL,
              `committee_cancel_date` datetime DEFAULT NULL,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `deleted_at` datetime DEFAULT NULL,
              `is_delete` enum('No','Yes') NOT NULL DEFAULT 'No',
              PRIMARY KEY (`plan_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            $tableTransaction = $this->db->query($queryCreateTable);
            if (!$tableTransaction) {
                $this->validation_lib->respondError('Gagal menambahkan tabel plan disbursement');
            }
        }

        $this->db->empty_table('tb_plan_disbursement');
        $data = [
            [
                'plan_id' => 1,
                'potential_borrower_name' => 'Petrochina International Jabung LDT',
                'id_param_business_field' => 1,
                'payor_code' => 'PAY00000001',
                'source_id' => '5',
                'agent_id' => '2',
                'id_backend_users' => '2',
                'plan_potency' => '1000000000',
                'actual_plan' => '0',
                'company_since' => '2018-09-24',
                'cooperation_since' => "2022-1-1",
            ],
            [
                'plan_id' => 2,
                'potential_borrower_name' => 'Digital Solusindo Bestama',
                'id_param_business_field' => 1,
                'payor_code' => 'PAY00000045',
                'source_id' => '5',
                'agent_id' => '3',
                'id_backend_users' => '2',
                'plan_potency' => '2000000000',
                'actual_plan' => '0',
                'company_since' => '2019-10-24',
                'cooperation_since' => "2021-8-19",
            ],
            [
                'plan_id' => 3,
                'potential_borrower_name' => 'PT Brataco',
                'id_param_business_field' => 1,
                'payor_code' => 'PAY00000014',
                'source_id' => '6',
                'agent_id' => '-',
                'id_backend_users' => '3',
                'plan_potency' => '5000000000',
                'actual_plan' => '1000000000',
                'company_since' => "2017-1-10",
                'cooperation_since' => "2018-1-10",
            ],

        ];
        foreach ($data as $key => $value) {
            $check = $this->db->get_where('tb_plan_disbursement', ['plan_id' => $value['plan_id']])->row();
            if (empty($check)) {
                $this->db->insert('tb_plan_disbursement', $value);
            } else {
                $this->db->update('tb_plan_disbursement', ['potential_borrower_name' => $value['potential_borrower_name']]);
            }
        }

        //LEVEL PRIORITIES
        if (!$this->db->table_exists('tb_param_level_priorities')) {
            $tableQuery = "CREATE TABLE `tb_param_level_priorities` (
                `id_level` int(11) NOT NULL AUTO_INCREMENT,
                `level_name` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id_level`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            $proses = $this->db->query($tableQuery);
            if (!$proses) {
                $this->validation_lib->respondError('Gagal menambahkan tabel tb_param_level_priorities');
            }
        }

        $this->db->empty_table('tb_param_level_priorities');
        $data = [
            [
                'id_level' => 1,
                'level_name' => 'High Priorities',
            ],
            [
                'id_level' => 2,
                'level_name' => 'Medium Priorities',
            ],
            [
                'id_level' => 3,
                'level_name' => 'Low Priorities',
            ]
        ];
        foreach ($data as $key => $value) {
            $check = $this->db->get_where('tb_param_level_priorities', ['id_level' => $value['id_level']])->row();
            if (empty($check)) {
                $this->db->insert('tb_param_level_priorities', $value);
            } else {
                $this->db->update('tb_param_level_priorities', ['level_name' => $value['level_name']]);
            }
        }



        if (!$this->db->table_exists('tb_param_level_risk')) {
            $tableQuery = "CREATE TABLE `tb_param_level_risk` (
                `id_level` int(11) NOT NULL AUTO_INCREMENT,
                `level_name` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id_level`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            $proses = $this->db->query($tableQuery);
            if (!$proses) {
                $this->validation_lib->respondError('Gagal menambahkan tabel Tabel level Risk');
            }
        }

        //LEVEL RISK
        $this->db->empty_table('tb_param_level_risk');
        $data = [
            [
                'id_level' => 1,
                'level_name' => 'High Risk',
            ],
            [
                'id_level' => 2,
                'level_name' => 'Medium Risk',
            ],
            [
                'id_level' => 3,
                'level_name' => 'Low Risk',
            ]
        ];
        foreach ($data as $key => $value) {
            $check = $this->db->get_where('tb_param_level_risk', ['id_level' => $value['id_level']])->row();
            if (empty($check)) {
                $this->db->insert('tb_param_level_risk', $value);
            } else {
                $this->db->update('tb_param_level_risk', ['level_name' => $value['level_name']]);
            }
        }

        if (!$this->db->table_exists('tb_master_tier')) {
            $tableQuery = "CREATE TABLE `tb_master_tier` (
              `id_tier` int(11) NOT NULL AUTO_INCREMENT,
              `tier_name` varchar(255) DEFAULT NULL,
              PRIMARY KEY (`id_tier`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            $proses = $this->db->query($tableQuery);
            if (!$proses) {
                $this->validation_lib->respondError('Gagal menambahkan tabel tb_master_tier');
            }
        }

        //MASTER TIER
        $this->db->empty_table('tb_master_tier');
        $data = [
            [
                'id_tier' => 1,
                'tier_name' => 'Tier 1',
            ],
            [
                'id_tier' => 2,
                'tier_name' => 'Tier 2',
            ],
            [
                'id_tier' => 3,
                'tier_name' => 'Tier 3',
            ],
            [
                'id_tier' => 4,
                'tier_name' => 'Tier 4',
            ],
            [
                'id_tier' => 5,
                'tier_name' => 'Tier 5',
            ]
        ];
        foreach ($data as $key => $value) {
            $check = $this->db->get_where('tb_master_tier', ['id_tier' => $value['id_tier']])->row();
            if (empty($check)) {
                $this->db->insert('tb_master_tier', $value);
            }else{
                $this->db->where(['id_tier' => $value['id_tier']])->update('tb_master_tier', ['tier_name' => $value['tier_name']]);
            }
        }

        $this->validation_lib->respondSuccess('Tabel param pipline berhasil diperbaharui');
    }
}
