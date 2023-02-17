<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

/**
 * Class Param_level_priorities
 *
 * @property Param_level_priorities_model Param_level_priorities_model
 * @property Authentication authentication
 * @property Validation_lib validation_lib
 */
class Param_level_priorities extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->authentication->init();
        date_default_timezone_set("Asia/Jakarta");

        $this->load->model(Param_level_priorities_model::class, 'model');
    }

    public function index_get($id = 0)
    {
        if (isset($_GET['filters'])) {
            $filters = $_GET['filters'];
        } else {
            $filters = array();
        }
        if (!empty($id)) {
            $data = $this->model->find($id);
        } else {
            $data = $this->model->all($filters);
        }
        $this->validation_lib->respondSuccess($data);
    }



    public function content_type_base64($encode_base64)
    {
        $decode = base64_decode($encode_base64);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $decode, FILEINFO_MIME_TYPE);

        return $mime_type;
    }
}
