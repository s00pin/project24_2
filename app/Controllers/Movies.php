<?php

namespace App\Controllers;

class Movies extends BaseController
{
    public function index()
    {
        return redirect()->to('/media');
    }
}
