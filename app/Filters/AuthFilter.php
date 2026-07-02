<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            $path = ltrim($request->getUri()->getPath(), '/');
            if (str_starts_with($path, 'api/') || str_starts_with($path, 'index.php/api/')) {
                return service('response')->setStatusCode(401)->setJSON([
                    'ok' => false,
                    'message' => 'Authentication required.',
                ]);
            }
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}
