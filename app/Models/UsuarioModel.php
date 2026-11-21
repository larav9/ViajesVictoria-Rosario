<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'usuario', 'clave', 'nombre', 'apellido', 
        'email', 'telefono_whatsapp', 'rol',
    ];

        // Activamos marcas de tiempo y borrado lógico
    protected $useTimestamps  = true;
    protected $createdField   = 'fecha_registro';
    protected $updatedField   = 'fecha_modificado';
    protected $useSoftDeletes = true;
    protected $deletedField   = 'fecha_borrado';

}