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
        $this->load->database();

        if ($this->db->table_exists('example')) {
            $this->validation_lib->respondError('Table sudah tersedia!');
        } else {
            //        bikin query untuk create table - TO DO dan alter table
            $queryCreateTable = "CREATE TABLE `example` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(200) NOT NULL,
              `display_name` varchar(30) DEFAULT NULL,
              `description` varchar(500) DEFAULT NULL,
              `status` tinyint(1) DEFAULT 1,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL,
              `deleted_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC";
            $tableTransaction = $this->db->query($queryCreateTable);
            if ($tableTransaction) {
                $this->validation_lib->respondSuccess('Berhasil menambahkan kolom');
            } else {
                $this->validation_lib->respondSuccess('Gagal menambahkan kolom');
            }
        }
    }
}
