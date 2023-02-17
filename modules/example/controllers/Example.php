<?php
defined('BASEPATH') or exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;

/**
 * Class Example
 *
 * @property Example_model example_model
 * @property Authentication authentication
 * @property Validation_lib validation_lib
 */
class Example extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->authentication->init();

        $this->load->model(Example_model::class, 'example_model');
    }

    public function index_get($id = 0)
    {
        if (!empty($id)) {
            $data = $this->example_model->find($id);
            if(!$data){
                $message['error'] = 'Data tidak ditemukan!';
                $this->validation_lib->respondNotFound($message);
            }

        } else {
            $data = $this->example_model->all();
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
        $create = $this->example_model->add($data);

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
        $dataPut = $this->example_model->edit($id, $data);
        if($dataPut){
            $this->validation_lib->respondSuccess($dataPut);
        }else{
            $this->validation_lib->respondError('Gagal melakukan perubahan data!');
        }
    }

    public function index_delete($id)
    {
        $dataDelete = $this->example_model->delete($id);
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
