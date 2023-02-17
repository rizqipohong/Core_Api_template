<?php
defined('BASEPATH') or exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;

/**
 * Class Tipe_industri
 *
 * @property Master_bidang_usaha_model model
 * @property Authentication authentication
 * @property Validation_lib validation_lib
 */
class Master_tier extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->authentication->init();

        $this->load->model(Master_tier_model::class, 'model');
    }

    public function index_get($id = '')
    {
        if ($id != '') {
            $filters['id_tier'] = $id;
            $data = $this->model->find($filters);
        } else {
            $data = $this->model->all();
        }
        $this->validation_lib->respondSuccess($data);
    }
}
