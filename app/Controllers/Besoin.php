<?php

namespace App\Controllers;

class Besoin extends BaseController
{
    public function index(): string
    {
        return view('besoin_liste');
    }
}
