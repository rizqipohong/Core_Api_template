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

        $this->validation_lib->respondSuccess('Tabel level Risk berhasil diperbaharui');
    }
}
