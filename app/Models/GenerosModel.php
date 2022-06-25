<?php

namespace App\Models;

use CodeIgniter\Model;

class GenerosModel extends Model
{
    protected $table = 'generos';
    protected $primarykey = 'CodigoGenero';
    protected $allowedFields = ['CodigoGenero', 'NombreGenero'];
}