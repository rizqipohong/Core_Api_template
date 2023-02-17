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

        $this->validation_lib->respondSuccess('Tabel tier berhasil diperbaharui');
    }
}
