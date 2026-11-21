<?php
namespace App\Controllers;
use App\Models\VehiculosModel;

class Vehiculos extends BaseController
{
    protected $vehiculos;

    public function __construct()
    {
        $this->vehiculos = new VehiculosModel();
    }

    public function insertar()
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $datos = [
            'id_chofer'           => session()->get('id_usuario'),
            'marca'               => $this->request->getPost('marca'),
            'modelo'              => $this->request->getPost('modelo'),
            'anio'                => $this->request->getPost('anio'),
            'color'               => $this->request->getPost('color'),
            'capacidad_pasajeros' => $this->request->getPost('capacidad_pasajeros'),
            'tiene_seguro'        => $this->request->getPost('tiene_seguro'),
            'aseguradora'         => $this->request->getPost('aseguradora'),
            'vencimiento_seguro'  => $this->request->getPost('vencimiento_seguro') ?: null
        ];

        $this->vehiculos->insert($datos);
        return redirect()->to(base_url('chofer/gestion'));
    }

    public function actualizar()
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $id = $this->request->getPost('id');
        
        $datos = [
            'marca'               => $this->request->getPost('marca'),
            'modelo'              => $this->request->getPost('modelo'),
            'anio'                => $this->request->getPost('anio'),
            'color'               => $this->request->getPost('color'),
            'capacidad_pasajeros' => $this->request->getPost('capacidad_pasajeros'),
            'tiene_seguro'        => $this->request->getPost('tiene_seguro'),
            'aseguradora'         => $this->request->getPost('aseguradora'),
            'vencimiento_seguro'  => $this->request->getPost('vencimiento_seguro') ?: null
        ];

        $this->vehiculos->where('id_chofer', session()->get('id_usuario'))->update($id, $datos);
        return redirect()->to(base_url('chofer/gestion'));
    }

    public function eliminar($id)
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $this->vehiculos->where('id_chofer', session()->get('id_usuario'))->delete($id);
        return redirect()->to(base_url('chofer/gestion'));
    }
}