<?php
namespace App\Models;

use CodeIgniter\Model;

class VehiculosModel extends Model
{
    protected $table            = 'vehiculos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields= [
        'id_chofer', 'marca', 'modelo', 'anio', 'color', 
        'capacidad_pasajeros', 'tiene_seguro', 'aseguradora', 'vencimiento_seguro'
    ];
       // Activamos marcas de tiempo y borrado lógico
    protected $useTimestamps  = true;
    protected $createdField   = 'fecha_registro';
    protected $updatedField   = 'fecha_modificado';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'fecha_borrado';

    

}