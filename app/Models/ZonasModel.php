<?php
namespace App\Models;

use CodeIgniter\Model;

class ZonasModel extends Model
{
    protected $table            = 'zonas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    protected $returnType       = 'array';
    
    protected $allowedFields    = ['id_chofer', 'nombre'];
        // Activamos marcas de tiempo y borrado lógico
    protected $useTimestamps  = true;
    protected $createdField   = 'fecha_registro';
    protected $updatedField   = 'fecha_modificado';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'fecha_borrado';

}