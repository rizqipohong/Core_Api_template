<?php
defined('BASEPATH') or exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;

/**
 * Class Example
 *
 * @property product_model product_model
 * @property Authentication authentication
 * @property Validation_lib validation_lib
 */
class Product extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->authentication->init();

        $this->load->model(Product_model::class, 'product_model');
    }

    public function index_get($id = 0)
    {
        if (isset($_GET['filters'])) {
            $filters = $_GET['filters'];
        } else {
            $filters = array();
        }
        if (!empty($id)) {
            $data = $this->product_model->find($id);
        } else {
            $data = $this->product_model->all($filters);
        }
        $this->validation_lib->respondSuccess($data);
    }

    public function index_post()
    {
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('name', 'nama role', 'trim');
        $this->form_validation->set_rules('description', 'Deskripsi role', 'trim|required');

        if (!$this->form_validation->run()) {
            $errors = $this->form_validation->get_array_errors();
            $this->validation_lib->respondError($errors);
        }
        $data = $this->post();
        $create = $this->product_model->add($data);

        $this->validation_lib->respondSuccess($create);

    }

    public function index_put($id)
    {
        $this->form_validation->set_data($this->put());
        $this->form_validation->set_rules('name', 'nama role', 'trim|required');
        $this->form_validation->set_rules('description', 'Deskripsi role', 'trim|required');
        if (!$this->form_validation->run()) {
            $errors = $this->form_validation->get_array_errors();
            $this->validation_lib->respondError($errors);
        }
        $data = $this->put();
        $dataPut = $this->product_model->edit($id, $data);
        if($dataPut){
            $this->validation_lib->respondSuccess($dataPut);
        }else{
            $this->validation_lib->respondError('Gagal melakukan perubahan data!');
        }
    }

    public function index_delete($id)
    {
        $dataDelete = $this->product_model->delete($id);
        if($dataDelete){
            $this->validation_lib->respondSuccess($dataDelete);
        }else{
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
