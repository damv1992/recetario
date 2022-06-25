<?php

namespace App\Models;

use CodeIgniter\Model;

class PlataformaModel extends Model
{
    protected $table = 'plataformas';
    protected $primarykey = 'CodigoPlataforma';
    protected $allowedFields = ['CodigoPlataforma', 'NombrePlataforma', 'IconoPlataforma'];
}