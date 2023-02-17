<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Class Authentication
 *
 */
class Authentication
{
    /*
    |--------------------------------------------------------------------------
    | Auth Library
    |--------------------------------------------------------------------------
    |
    | This Library handles authenticating users for the application and
    | redirecting them to your home screen.
    |
    */
    protected $CI;


    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Initialization the Auth class
     */
    public function init()
    {
        $this->authtoken();
    }

    public function authtoken()
    {
        $token = null;
        if (!isset($this->CI->input->request_headers()['Authorization'])) {
            $this->show_error('Token tidak ditemukan!');
        }
        $authHeader = $this->CI->input->request_headers()['Authorization'];
        $arr = explode(" ", $authHeader);
        if(isset($arr[1])){
            $token = $arr[1];
            if ($token) {
                try {
                    $this->CI->load->model(User_model::class, 'user_model');
                    $secret_key = $this->getConfigToken()['secretkey'];

                    $isValid = $this->CI->user_model->getByToken($token);
                    if (!$isValid) {
                        $this->show_error('Token salah!');
                    } else {
                        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
                        return true;
                    }
                } catch (\Exception $e) {
                    $this->show_error($e->getMessage());
                }
            }
        }else{
            $this->show_error('Token tidak ditemukan!');
        }
    }

    public function checktoken()
    {
        $token = null;
        if (!isset($this->CI->input->request_headers()['Authorization'])) {
            return false;
        }
        $authHeader = $this->CI->input->request_headers()['Authorization'];
        $arr = explode(" ", $authHeader);
        $token = $arr[1];
        if ($token) {
            try {
                $this->CI->load->model(User_model::class, 'user_model');
                $secret_key = $this->getConfigToken()['secretkey'];

                $isValid = $this->CI->user_model->getByToken($token);
                if (!$isValid) {
                    return false;
                } else {
                    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    function getConfigToken()
    {
        $cnf['secretkey'] = 'sanderspanel';
        return $cnf;
    }

    public function show_data($data, $status_code = 200)
    {
        header('Content-Type: application/json; charset=utf-8', true, $status_code);
        $response['data'] = $data;
        $response['success'] = true;
        echo json_encode($response);
    }

    public function show_error($message, $template = 'error_general', $status_code = 500)
    {
        header('Content-Type: application/json; charset=utf-8', true, $status_code);
        $response = [
            'error' => [
                'domain' => 'Exception',
                'message' => $message
            ],
            'success' => false
        ];
        echo json_encode($response);
        die();

    }
}
