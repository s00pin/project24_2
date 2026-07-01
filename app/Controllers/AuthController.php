<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        helper(['form', 'url']);

        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $username = trim((string) $this->request->getPost('username'));
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);

            if ($user) {
                if (password_verify($password, $user['password_hash'])) {
                    $session = session();
                    $session->set([
                        'user_id' => $user['user_id'],
                        'username' => $user['username'],
                        'logged_in' => true,
                    ]);

                    $userModel->updateLastLogin($user['user_id']);

                    return redirect()->to('/dashboard');
                }

                return redirect()->back()->withInput()->with('error', 'Invalid password.');
            }

            return redirect()->back()->withInput()->with('error', 'User not found.');
        }

        $data = [
            'title' => 'Login',
            'authMode' => 'login',
        ];
        return view('templates/header', $data)
            . view('auth/login', $data)
            . view('templates/footer');
    }

    public function register()
    {
        helper(['form', 'url']);

        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/login');
        }

        $username = trim((string) $this->request->getPost('reg_username'));
        $email = trim((string) $this->request->getPost('reg_email'));
        $password = (string) $this->request->getPost('reg_password');
        $confirm = (string) $this->request->getPost('reg_password_confirm');

        if ($username === '' || $email === '' || $password === '') {
            return redirect()->back()->withInput()->with('register_error', 'All fields are required.');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('register_error', 'Please enter a valid email address.');
        }

        if (mb_strlen($password) < 8) {
            return redirect()->back()->withInput()->with('register_error', 'Password must be at least 8 characters.');
        }

        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('register_error', 'Passwords do not match.');
        }

        $userModel = new UserModel();
        if ($userModel->getUserByUsername($username)) {
            return redirect()->back()->withInput()->with('register_error', 'Username is already taken.');
        }
        if ($userModel->getUserByEmail($email)) {
            return redirect()->back()->withInput()->with('register_error', 'Email is already registered.');
        }

        $userId = $userModel->insert([
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if (! $userId) {
            return redirect()->back()->withInput()->with('register_error', 'Unable to create account. Please try again.');
        }

        session()->set([
            'user_id' => (int) $userId,
            'username' => $username,
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard')->with('success', 'Account created successfully.');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/home');
    }
}
