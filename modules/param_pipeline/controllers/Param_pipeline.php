<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

/**
 * Class Param_agent
 *
 * @property Param_pipeline_model param_pipeline_model
 * @property Authentication authentication
 * @property Validation_lib validation_lib
 */
class Param_pipeline extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->authentication->init();
        date_default_timezone_set("Asia/Jakarta");

        $this->load->model(Param_pipeline_model::class, 'model');
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

    public function index_post()
    {
        $data = $this->post();
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules('pipeline_origin', 'pipline asal', 'trim|required');
        $this->form_validation->set_rules('level_of_priorities', 'level of priorities', 'trim|required');
        $this->form_validation->set_rules('level_of_risk', 'level of risk', 'trim|required');
        $this->form_validation->set_rules('tier', 'tier', 'numeric|required');
        if (!$this->form_validation->run()) {
            $errors = $this->form_validation->get_array_errors();
            $this->validation_lib->respondError($errors);
        }
        $create = $this->model->add($data);

        $this->validation_lib->respondSuccess($create);
    }

    public function index_put($id)
    {
        $data = $this->put();
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('pipeline_origin', 'pipline asal', 'trim|required');
        $this->form_validation->set_rules('level_of_priorities', 'level of priorities', 'trim|required');
        $this->form_validation->set_rules('level_of_risk', 'level of risk', 'trim|required');
        $this->form_validation->set_rules('tier', 'tier', 'numeric|required');
        if (!$this->form_validation->run()) {
            $errors = $this->form_validation->get_array_errors();
            $this->validation_lib->respondError($errors);
        }
        $dataPut = $this->model->edit($id, $data);
        if ($dataPut) {
            $this->validation_lib->respondSuccess($dataPut);
        } else {
            $this->validation_lib->respondError('Gagal melakukan perubahan data!');
        }
    }

    public function index_delete($id)
    {
        $dataDelete = $this->model->delete($id);
        if ($dataDelete) {
            $this->validation_lib->respondSuccess($dataDelete);
        } else {
            $this->validation_lib->respondError('Gagal melakukan penghapusan data!');
        }
    }

    public function content_type_base64($encode_base64)
    {
        $decode = base64_decode($encode_base64);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $decode, FILEINFO_MIME_TYPE);

        return $mime_type;
    }
}
