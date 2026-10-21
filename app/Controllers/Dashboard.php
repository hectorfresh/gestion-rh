<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('dashboard', [
            'nom'  => session()->get('nom'),
            'role' => session()->get('role'),
        ]);
    }
}