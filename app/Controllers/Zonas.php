<?php
namespace App\Controllers;

use App\Models\ZonasModel;

class Zonas extends BaseController
{
    protected $zonas;

    public function __construct()
    {
        $this->zonas = new ZonasModel();
    }

    public function insertar()
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $datos = [
            'id_chofer' => session()->get('id_usuario'),
            'nombre'    => $this->request->getPost('nombre')
        ];

        $this->zonas->insert($datos);
        return redirect()->to(base_url('chofer/gestion'));
    }

    public function actualizar()
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $id = $this->request->getPost('id');
        
        $datos = [
            'nombre' => $this->request->getPost('nombre')
        ];

        $this->zonas->where('id_chofer', session()->get('id_usuario'))->update($id, $datos);
        return redirect()->to(base_url('chofer/gestion'));
    }

    public function eliminar($id)
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $this->zonas->where('id_chofer', session()->get('id_usuario'))->delete($id);
        return redirect()->to(base_url('chofer/gestion'));
    }
}