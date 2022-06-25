<?php

namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

use App\Models\AlquileresModel;
use App\Models\ProductosModel;

class Home extends BaseController {

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

        $this->alquileres = new AlquileresModel();
        $this->productos = new ProductosModel();
	}

	public function datosPrincipales() {
		$configuracion = $this->configuraciones->first();
		$socialess = $this->sociales->findAll();
		$publicidadess = $this->publicidades->where([
			'FechaHoraInicio <=' => date('Y-m-d'),
			'FechaHoraFin >=' => date('Y-m-d'),
			'EstadoPublicidad' => 1
		])->orderBy('CodigoPublicidad', 'DESC')->findAll();
		$plataformass = $this->plataformas->orderBy('NombrePlataforma ASC')->findAll();
		$pagoss = $this->pagos->findAll();
        $ip = gethostbyname('www.google.com');
        //$ip = $_SERVER['REMOTE_ADDR'];
        $carritoss = $this->carritos->where('DireccionIp', $ip)->findAll();
		$datos = [
			'configuracion' => $configuracion,
			'sociales' => $socialess,
			'publicidades' => $publicidadess,
			'plataformas' => $plataformass,
			'pagos' => $pagoss,
			'carritos' => $carritoss
		];
		return $datos;
	}

	public function index() {
		$configuracion = $this->configuraciones->orderby('CodigoConfiguracion DESC')->first();
		if ($configuracion['CodigoConfiguracion'] == "") $this->generarDatosPagina();
		$usuario = $this->usuarios->first();
		if ($usuario['CodigoUsuario'] == "") $this->generarCuentaAdministrador();
		$productoss = $this->productos->orderBy('NombreProducto')->findAll(4);
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Inicio',
			'productos' => $productoss
		];
		return view('index', $datos);
	}

	public function alquiler() {
		$datos = $this->datosPrincipales();
		$alquileress = $this->alquileres->orderBy('NombreProducto ASC')->findAll();
		$total = 0;
		foreach ($alquileress as $alquiler) {
			$total += $alquiler['Precio'];
		}
		$datos += [
			'titulo' => 'Alquiler de cuenta',
			'alquileres' => $alquileress,
			'total' => $total
		];
		return view('alquiler', $datos);
	}

	public function generarDatosPagina() {
		$this->configuraciones->insert([
			'CodigoConfiguracion' => 1,
			'NombrePagina' => 'Games Shop PS4',
			'IconoPagina' => '/GoodGames/assets/images/favicon.png',
			'LogoPagina' => '/GoodGames/assets/images/logo.png',
			'FrasePagina' => 'Disfruta de tus juegos favoritos sin límites.',
			'SobreNosotros' => '',
			'EstadoPagina' => 1,
		]);
	}

	public function generarCuentaAdministrador() {
		$this->usuarios->insert([
			'CodigoUsuario' => 1,
			'Usuario' => 'Memesis1992',
			'Contrasena' => 'memesis181214',
			'Telefono' => '59173354006',
			'RolAsignado' => 'Administrador',
			'FechaHoraRegistro' => '2022-04-19 08:00:00'
		]);
	}
}
