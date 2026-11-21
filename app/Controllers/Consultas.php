<?php
namespace App\Controllers;

use App\Models\ConsultasModel;
use App\Models\ViajeModel;

class Consultas extends BaseController
{
    public function index()
    {
        if (session()->get('rol') !== 'chofer') { // muestra en el panel de choferes las consultas que le hicieron los pasajeros a sus viajes
            return redirect()->to(base_url('panel'));
        }

        $consultasModel = new ConsultasModel();
        $idChofer = session()->get('id_usuario');

        $datos = [
            'titulo'    => 'Consultas Recibidas',
            'consultas' => $consultasModel->select('consultas.*, usuarios.nombre as pasajero_n, usuarios.apellido as pasajero_a, viajes.origen, viajes.destino, viajes.hora_salida')
                ->join('viajes', 'viajes.id = consultas.id_viaje')
                ->join('usuarios', 'usuarios.id = consultas.id_pasajero')
                ->where('consultas.id_chofer', $idChofer)
                ->orderBy('consultas.fecha', 'DESC')
                ->findAll()
        ];

        return view('consultas/listado', $datos); //desp de la consulta, manda los datos a la vista
    }

    public function guardar() 
   /** consultas.js manda el id_viaje y el token por post, PHP guarda en la BD y responde 
    *un JSON (true/false + token nuevo), si dio true abre WhatsApp; si dio false tira el alerta.*/
  
    {
        $idPasajero = session()->get('id_usuario'); 

        if (!$idPasajero) {
            return $this->response->setJSON([   //solo los q inicen sesion pueden consultar, devuelve un mensaje de error
                'success' => false,
                'mensaje' => 'Tenés que iniciar sesión para consultar.',
                'token'   => csrf_hash()
            ]);
        }

        $idViaje = $this->request->getPost('id_viaje');
        $viajeModel = new ViajeModel();
        $viaje = $viajeModel->find($idViaje); // se busca el viaje para ver q exista

        if (!$viaje) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El viaje seleccionado no existe.', //error si no existe
                'token'   => csrf_hash()  //se resetea el token para q no caduque y pueda volver a enviar la consulta
            ]);
        }

        $consultasModel = new ConsultasModel();

        $datos = [
            'id_viaje'    => $idViaje,
            'id_chofer'   => $viaje['id_chofer'],  //aca se busca en el viaje, el id chofer (de ese viaje)
            'id_pasajero' => $idPasajero
        ];

        if (!$consultasModel->insert($datos)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo guardar la consulta.', // si no hay consulta, devuelve un mensaje de error
                'token'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'token'   => csrf_hash()  //si se guarda , el token tmb se resetea 
        ]);
    }
}
