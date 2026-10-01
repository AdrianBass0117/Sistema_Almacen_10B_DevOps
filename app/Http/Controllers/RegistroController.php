<?php

namespace App\Http\Controllers;

use App\Models\Registro;

class RegistroController extends Controller
{
    public function index()
    {
        $registros = Registro::paginate(50);
        return view('registros.index', compact('registros'));
    }
}