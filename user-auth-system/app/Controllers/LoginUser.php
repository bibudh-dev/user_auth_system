<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;

class LoginUser extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    }

    public function index()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $this->request->getVar('email'))->first();

        if (!$user || !password_verify($this->request->getVar('password'), $user['password'])) {
            return $this->failUnauthorized('Invalid email or password');
        }

        $key = getenv('JWT_SECRET');
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600, // Token expires in 1 hour
            'uid' => $user['id'],
            'email' => $user['email']
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond(['token' => $token]);
    }
}