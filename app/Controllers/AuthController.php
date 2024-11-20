<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    public function login()
    {
        $data = $this->request->getPost();
        $model = new UserModel();
        $user = $model->where('email', $data['email'])->first();

        if ($user && password_verify($data['password'], $user['password'])) {
            $key = getenv('JWT_SECRET');
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
            $payload = array(
                "iss" => "localhost", // Issuer
                "iat" => $issuedAt,   // Issued At
                "exp" => $expirationTime,  // Expiration time
                "user_id" => $user['id']
            );

            $jwt = JWT::encode($payload, $key);

            return $this->respond([
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $jwt
            ]);
        } else {
            return $this->failUnauthorized('Invalid credentials');
        }
    }

    public function register()
{
    // Ambil data JSON
    $data = $this->request->getJSON(true);

    if (empty($data)) {
        return $this->fail('No data received.', 400);
    }

    // Validasi field
    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
        return $this->fail('Required fields are missing.', 400);
    }

    try {
        $model = new \App\Models\UserModel();

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Data untuk disimpan
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
        ];

        // Simpan ke database
        if ($model->insert($userData)) {
            return $this->respondCreated([
                'status' => 'success',
                'message' => 'User registered successfully',
            ]);
        }

        return $this->fail('Registration failed. Please try again.', 500);
    } catch (\Exception $e) {
        return $this->failServerError('An unexpected error occurred: ' . $e->getMessage());
    }
}



}
