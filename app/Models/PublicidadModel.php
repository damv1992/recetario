<?php

namespace App\Models;

use CodeIgniter\Model;

class PublicidadModel extends Model
{
    protected $table = 'publicidades';
    protected $primarykey = 'CodigoPublicidad';
    protected $allowedFields = ['CodigoPublicidad', 'Titular', 'Descripcion', 'ImagenPublicidad', 'EnlacePublicidad', 'EstadoPublicidad', 'FechaHoraInicio', 'FechaHoraFin'];
}