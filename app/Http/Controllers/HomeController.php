<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function inicio()
    {
        return view('inicio');
    }

    public function nosotros()
    {
        return view('nosotros');
    }

    public function catalogo()
    {
        return view('catalogo');
    }

    public function ubicacion()
    {
        return view('ubicacion');
    }

    public function usuario()
    {
        return view('usuario');
    }

    public function carrito()
    {
        return view('carrito');
    }
}

