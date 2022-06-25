<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table = 'configuracion';
    protected $primarykey = 'CodigoConfiguracion';
    protected $allowedFields = ['CodigoConfiguracion', 'NombrePagina', 'LogoPagina', 'IconoPagina', 'FrasePagina', 'SobreNosotros', 'EstadoPagina'];
}