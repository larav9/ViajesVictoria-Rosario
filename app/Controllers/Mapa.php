<?php
namespace App\Controllers;

class Mapa extends BaseController
{
    public function index()
    {
        if (session()->get('rol') !== 'chofer') {
            return redirect()->to(base_url('panel'));
        }

        $datos = [
            'titulo' => 'Calculadora de Rutas'
        ];
        
        echo view('templates/header', $datos);
        echo view('mapa/index', $datos);
        echo view('templates/footer');
    }
}