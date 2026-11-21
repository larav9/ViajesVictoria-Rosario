<?php
namespace App\Controllers;

use App\Models\ViajeModel;
use App\Models\VehiculosModel;
use App\Models\ZonasModel;

class Chofer extends BaseController
{
    public function gestion()
    {
        if (session()->get('rol') !== 'chofer') {
            return redirect()->to(base_url('panel'));
        }

        $id_chofer = session()->get('id_usuario');
        
        $viajeModel = new ViajeModel();
        $vehiculosModel = new VehiculosModel();
        $zonasModel = new ZonasModel();
        $db = \Config\Database::connect(); //instanciamos la bbdd para no crear un modelo de las tablas zonas,dias y auditoria

        $viajesEncontrados = $viajeModel->where('id_chofer', $id_chofer) //el chofer solo ve sus viajes, no los de otros
                                       ->where('fecha_borrado', null)
                                       ->findAll();

        foreach ($viajesEncontrados as &$v) {
            $v['zonas'] = $db->table('viaje_zonas')
                             ->select('viaje_zonas.recargo, zonas.nombre, viaje_zonas.id_zona')
                             ->join('zonas', 'zonas.id = viaje_zonas.id_zona')
                             ->where('id_viaje', $v['id'])
                             ->get()->getResultArray();
            $v['dias']  = $db->table('viaje_dias')
                             ->where('id_viaje', $v['id'])
                             ->get()->getResultArray();
        }

        $data = [
            'titulo'    => 'Mi Gestión',
            'viajes'    => $viajesEncontrados, //aca le pasamos el array de viajes con sus zonas y dias
            'vehiculos' => $vehiculosModel->where('id_chofer', $id_chofer)->findAll(),
            'zonas'     => $zonasModel->where('id_chofer', $id_chofer)->findAll(),
        ];

        return view('chofer/gestion', $data);
    }
}