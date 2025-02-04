<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PublicController extends BaseController
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
        $key = getenv('JWT_SECRET');
        $header = $this->request->getHeaderLine('Authorization');
        $token = null;

        if (!empty($header) && preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }

        if (!$token) {
            return $this->failUnauthorized('Token not found');
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $this->respond(['message' => 'Welcome, ' . $decoded->email]);
        } catch (\Exception $e) {
            return $this->failUnauthorized('Invalid token');
        }
    }
}