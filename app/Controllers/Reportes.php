<?php
namespace App\Controllers;

class Reportes extends BaseController
{
    public function index()
    {
        if (session()->get('rol') !== 'chofer') { // solo chofer
            return redirect()->to(base_url('panel'));
        }

        $db = \Config\Database::connect();
        $idChofer = session()->get('id_usuario');

        //unimos consultas con viajes y viaje_dias (left join porq los eventuales no tienen dias fijos en esa tabla)
        //y filtramos fecha_borrado null para no contar las consultas eliminadas
        $diasQuery = $db->table('consultas')
            ->select('viajes.tipo, viajes.fecha, viaje_dias.dia_semana')
            ->join('viajes', 'viajes.id = consultas.id_viaje')
            ->join('viaje_dias', 'viaje_dias.id_viaje = viajes.id', 'left')
            ->where('consultas.id_chofer', $idChofer)
            ->where('consultas.fecha_borrado', null)
            ->get()->getResultArray();

        //inicializamos los 7 dias en 0 para q aparezcan todos en el grafico aunque no tengan consultas
        $conteoDias = [
            'Lunes'     => 0,
            'Martes'    => 0,
            'Miércoles' => 0,
            'Jueves'    => 0,
            'Viernes'   => 0,
            'Sábado'    => 0,
            'Domingo'   => 0,
        ];

        //diccionario para traducir el numero de dia q devuelve date('N') (1=lunes a 7=domingo)
        $mapaDias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        foreach ($diasQuery as $fila) {
            if (!empty($fila['dia_semana']) && isset($conteoDias[$fila['dia_semana']])) {
                //si es viaje diario, sumamos 1 directo al dia de la semana q tiene cargado
                $conteoDias[$fila['dia_semana']]++;
            } elseif ($fila['tipo'] === 'eventual' && !empty($fila['fecha'])) {
                //si es eventual, sacamos el numero de dia de su fecha puntual con date('N') y lo pasamos a texto
                $numDia = (int) date('N', strtotime($fila['fecha']));
                $nombreDia = $mapaDias[$numDia];
                $conteoDias[$nombreDia]++;
            }
        }

        //separamos las claves (nombres de dias) y los valores (cantidades) en 2 arrays para mandar a la vista
        $labelsDias  = array_keys($conteoDias);
        $valoresDias = array_values($conteoDias);

        // consulta de los horarios de salida más elegidos
        $horariosQuery = $db->table('consultas')
            ->select("DATE_FORMAT(viajes.hora_salida, '%H:%i') as horario, COUNT(consultas.id) as total") //el date format saca los seg
            ->join('viajes', 'viajes.id = consultas.id_viaje')
            ->where('consultas.id_chofer', $idChofer)
            ->where('consultas.fecha_borrado', null)
            ->groupBy('viajes.hora_salida')
            ->orderBy('viajes.hora_salida', 'ASC')
            ->get()->getResultArray();

        $labelsHorarios  = []; //lo mismo q con los dias, mandamos a la vista para mostrarlos
        $valoresHorarios = [];
        foreach ($horariosQuery as $fila) {
            $labelsHorarios[]  = $fila['horario'] . ' hs';
            $valoresHorarios[] = (int) $fila['total']; //se pasa a int 
        }

        $datos = [
            'titulo'          => 'Reportes de Demanda',
            'labelsDias'      => json_encode($labelsDias),
            'valoresDias'     => json_encode($valoresDias),
            'labelsHorarios'  => json_encode($labelsHorarios),
            'valoresHorarios' => json_encode($valoresHorarios),
             //usamos json encode porque necesitamos inyectarlos directo en el js de Chart.js dentro de reportes, 
             // y debemos traducir esos arrays a js para q los detecte como array
        ];

        echo view('templates/header', $datos);
        echo view('reportes/index', $datos);
        echo view('templates/footer');
    }
}