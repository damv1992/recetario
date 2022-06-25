<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primarykey = 'CodigoUsuario';
    protected $allowedFields = ['CodigoUsuario', 'Usuario', 'Contrasena', 'Telefono', 'RolAsignado', 'FechaHoraRegistro'];
}