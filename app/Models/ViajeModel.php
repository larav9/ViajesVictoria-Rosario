<?php
namespace App\Models;
use CodeIgniter\Model;

class ViajeModel extends Model
{
    protected $table = 'viajes';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'id_chofer', 'id_vehiculo', 'origen', 'destino', 'tipo', 'fecha', 'hora_salida',
        'capacidad_pasajeros', 'precio_base', 'observaciones'
    ];
    
    protected $useTimestamps  = true;
    protected $createdField   = 'fecha_registro';
    protected $updatedField   = 'fecha_modificado';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'fecha_borrado';
}