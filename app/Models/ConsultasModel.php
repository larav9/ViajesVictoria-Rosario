<?php
namespace App\Models;

use CodeIgniter\Model;

class ConsultasModel extends Model
{
    protected $table            = 'consultas';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'id_viaje',
        'id_chofer',
        'id_pasajero',
        'fecha'
    ];
    protected $useTimestamps  = false;
    protected $useSoftDeletes = true;
    protected $deletedField   = 'fecha_borrado';        
}