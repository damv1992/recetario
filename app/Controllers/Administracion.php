<?php

namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

class Administracion extends Home {

	public function __construct() {
		$this->session = \Config\Services::session();
        $this->session->start();
        $this->configuraciones = new ConfiguracionModel();
        $this->sociales = new RedesSocialesModel();
        $this->publicidades = new PublicidadModel();
        $this->plataformas = new PlataformaModel();
        $this->pagos = new MetodosPagoModel();
        $this->usuarios = new UsuarioModel();
        $this->carritos = new CarritoModel();
	}
	
	public function index() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Administración'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/index', $datos);
		else return redirect()->to(base_url());
	}

    public function pagina() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Configuración'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/pagina', $datos);
		else return redirect()->to(base_url());
    }
}