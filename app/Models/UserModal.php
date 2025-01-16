<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['username', 'email', 'password_hash', 'role', 'status', 'created_at', 'updated_at'];

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function updateLastLogin($user_id)
    {
        return $this->update($user_id, ['last_login' => date('Y-m-d H:i:s')]);
    }
}
