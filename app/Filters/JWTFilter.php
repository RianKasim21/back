<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;

class JWTFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $token = $request->getHeaderLine('Authorization');
        if (!$token) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON(['error' => 'No token provided']);
        }

        $key = getenv('JWT_SECRET');
        try {
            $decoded = JWT::decode($token, $key, ['HS256']);
            $request->user = $decoded;
        } catch (\Exception $e) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON(['error' => 'Invalid token']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
