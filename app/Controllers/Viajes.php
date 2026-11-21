<?php
namespace App\Controllers;

use App\Models\ViajeModel;

class Viajes extends BaseController
{
    protected $viajes;
    protected $db;

    public function __construct()
    {
        $this->viajes = new ViajeModel();
        $this->db = \Config\Database::connect();
        //instanciamos ademas del modelo la bbdd para no crear un modelo de las tablas zonas,dias y auditoria
    }

    public function index()
    {
        $viajesEncontrados = $this->viajes->select('viajes.*, usuarios.nombre as chofer_nombre, usuarios.apellido as chofer_apellido, usuarios.telefono_whatsapp, vehiculos.marca, vehiculos.modelo')
            ->join('usuarios', 'usuarios.id = viajes.id_chofer')
            ->join('vehiculos', 'vehiculos.id = viajes.id_vehiculo')
            ->findAll();

        foreach ($viajesEncontrados as &$v) { // aca usamos esas tablas 
            $v['zonas'] = $this->db->table('viaje_zonas')
                                   ->select('viaje_zonas.recargo, zonas.nombre, viaje_zonas.id_zona')
                                   ->join('zonas', 'zonas.id = viaje_zonas.id_zona')
                                   ->where('id_viaje', $v['id'])
                                   ->get()->getResultArray(); //traemos en un array 
            $v['dias']  = $this->db->table('viaje_dias')->where('id_viaje', $v['id'])->get()->getResultArray();
            //traemos en un array los dias de la semana que tiene ese viaje
        }

        $datos = ['titulo' => 'Cartelera de Viajes', 'viajes' => $viajesEncontrados]; // mandamos el array de zonas y dias al listadoviajes

        return view('viajes/listado', $datos);
    }

    public function insertar()
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel')); //solo chofer puede

        $post = $this->request->getPost();//obtenemos los datos del post
        $idChofer = session()->get('id_usuario'); //obtenemos el id del chofer de la sesion

        $datosViaje = [
            'id_chofer'           => $idChofer,
            'id_vehiculo'         => $post['id_vehiculo'],
            'origen'              => $post['origen'],
            'destino'             => $post['destino'],
            'tipo'                => $post['tipo'],
            'fecha'               => $post['tipo'] === 'eventual' ? $post['fecha'] : null, //si es eventual se guarda la fecha, si es diario queda null
            'hora_salida'         => $post['hora_salida'],
            'capacidad_pasajeros' => $post['capacidad_pasajeros'],
            'precio_base'         => $post['precio_base'],
            'observaciones'       => $post['observaciones'] ?? null,
        ];

        $this->db->transStart(); //transaccion para que si falla algo no se guarde nada

        $this->viajes->insert($datosViaje); //insertamos el viaje y obtenemos el id del viaje insertado
        $idViaje = $this->viajes->getInsertID();

        if ($datosViaje['tipo'] === 'diario' && !empty($post['dias'])) { // si es diario y hay dias seleccionados,
            foreach ($post['dias'] as $dia) {                            // los insertamos en la tabla viaje_dias
                $this->db->table('viaje_dias')->insert(['id_viaje' => $idViaje, 'dia_semana' => $dia]); //insertamos en la tabla viaje_dias el id del viaje y el dia de la semana
            }
        }

        if (!empty($post['zonas_seleccionadas'])) {
            foreach ($post['zonas_seleccionadas'] as $idZona) {
                $recargo = $post['recargo_' . $idZona] ?? 0;
                $this->db->table('viaje_zonas')->insert([
                    'id_viaje' => $idViaje,
                    'id_zona'  => $idZona,
                    'recargo'  => $recargo
                ]);
            } // insertamos en la tabla viaje_zonas el id del viaje, el id de la zona y el recargo corresp
        }

        $this->db->table('auditoria_viajes')->insert([
            'id_viaje'  => $idViaje,
            'id_chofer' => $idChofer,
            'accion'    => 'INSERTAR',
            'detalles'  => "Publicó viaje de {$datosViaje['origen']} a {$datosViaje['destino']} (\${$datosViaje['precio_base']})"
        ]); //se audita el insert

        $this->db->transComplete(); //completamos la transaccion
        return redirect()->to(base_url('chofer/gestion'));
    }

    public function actualizar()
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $post = $this->request->getPost();
        $idViaje = $post['id'];
        $idChofer = session()->get('id_usuario');

        $viajeExistente = $this->viajes->where('id', $idViaje) //se busca el viaje por id
                                       ->where('id_chofer', $idChofer)
                                       ->first();

        if (!$viajeExistente) {
            return redirect()->to(base_url('chofer/gestion')); //si no existe el viaje o no pertenece al chofer, redirigimos a la gestion
        }

        $datosViaje = [
            'id_vehiculo'         => $post['id_vehiculo'],
            'origen'              => $post['origen'],
            'destino'             => $post['destino'],
            'tipo'                => $post['tipo'],
            'fecha'               => $post['tipo'] === 'eventual' ? $post['fecha'] : null,
            'hora_salida'         => $post['hora_salida'],
            'capacidad_pasajeros' => $post['capacidad_pasajeros'],
            'precio_base'         => $post['precio_base'],
            'observaciones'       => $post['observaciones'] ?? null,
        ];

        $this->db->transStart(); 

        $this->viajes->update($idViaje, $datosViaje); //se hace el update

        $this->db->table('viaje_dias')->where('id_viaje', $idViaje)->delete();
        if ($datosViaje['tipo'] === 'diario' && !empty($post['dias'])) {
            foreach ($post['dias'] as $dia) {
                $this->db->table('viaje_dias')->insert(['id_viaje' => $idViaje, 'dia_semana' => $dia]);
            } // si es diario y hay dias seleccionados, se insertan en la tabla viaje_dias
        }

        $this->db->table('viaje_zonas')->where('id_viaje', $idViaje)->delete();
        if (!empty($post['zonas_seleccionadas'])) {
            foreach ($post['zonas_seleccionadas'] as $idZona) {
                $recargo = $post['recargo_' . $idZona] ?? 0;
                $this->db->table('viaje_zonas')->insert([
                    'id_viaje' => $idViaje,
                    'id_zona'  => $idZona,
                    'recargo'  => $recargo
                ]); // insertamos en la tabla viaje_zonas el id del viaje, el id de la zona y el recargo correspondiente
            }
        }

        $this->db->table('auditoria_viajes')->insert([
            'id_viaje'  => $idViaje,
            'id_chofer' => $idChofer,
            'accion'    => 'ACTUALIZAR',
            'detalles'  => "Modificó el viaje ID {$idViaje} (Origen: {$datosViaje['origen']} - Destino: {$datosViaje['destino']})"
        ]); //lo mismo auditamos tmb el update

        $this->db->transComplete();
        return redirect()->to(base_url('chofer/gestion'));
    }

    public function eliminar($id)
    {
        if (session()->get('rol') !== 'chofer') return redirect()->to(base_url('panel'));

        $idChofer = session()->get('id_usuario');

        $viaje = $this->viajes->where('id', $id)
                              ->where('id_chofer', $idChofer)
                              ->first();
        if ($viaje) { //si existe el viaje y pertenece al chofer, se hace la baja logica
            $this->db->transStart();

            $this->db->table('auditoria_viajes')->insert([
                'id_viaje'  => $id,
                'id_chofer' => $idChofer,
                'accion'    => 'ELIMINAR',
                'detalles'  => "Dio de baja lógica el viaje ID {$id} ({$viaje['origen']} a {$viaje['destino']})"
            ]); //audt la elim 

            $this->viajes->delete($id); //

            $this->db->transComplete();
        }
        return redirect()->to(base_url('chofer/gestion'));
    }
}