<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        helper(['form', 'url']);

        if ($this->request->getMethod() == 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            
            // Load the user model
            $userModel = new UserModel();
            
            // Check if user exists
            $user = $userModel->getUserByUsername($username);
            if ($user) {
                // Verify the password
                if (password_verify($password, $user['password_hash'])) {
                    // Password is correct, set session data
                    $session = session();
                    $session->set([
                        'user_id' => $user['user_id'],
                        'username' => $user['username'],
                        'logged_in' => true
                    ]);

                    // Update last login timestamp
                    $userModel->updateLastLogin($user['user_id']);

                    return redirect()->to('/dashboard');
                } else {
                    return redirect()->back()->with('error', 'Invalid password');
                }
            } else {
                return redirect()->back()->with('error', 'User not found');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
