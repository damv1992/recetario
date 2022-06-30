<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;

class Administrador extends Home {

	public function __construct() {
		$this->session = \Config\Services::session();
        $this->session->start();
		$this->configuraciones = new ConfiguracionModel();
	}

	public function index() {
		$datos = $this->datosPrincipales();
		if ($this->session->get('Usuario')) {
			$datos += [
				'titulo' => 'Administrador'
			];
			return view('administracion/index', $datos);
		} else {
			$datos += [
				'titulo' => 'Iniciar Sesión'
			];
			return view('administracion/login', $datos);
		}
    }

    public function login() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $usuario = $this->configuraciones->where('Usuario', $username)->first();
            if (!$usuario['Usuario']) return "no_existe";
            if ($usuario['Contraseña'] <> $password) return "incorrecto";
            $this->session->set([
				'Usuario' => $usuario['Usuario'],
				'Contraseña' => $usuario['Contraseña']
			]);
            return "ok";
        } else return "error";
    }

    public function logout() {
        $this->session->destroy();
        return redirect()->to(base_url());
    }

	public function pagina() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Datos de la página'
		];
		if ($this->session->get('Usuario')) return view('administracion/pagina', $datos);
		else return redirect()->to(base_url());
    }
	
	public function recetas() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Categorías'
		];
		if ($this->session->get('Usuario')) return view('administracion/categorias/index', $datos);
		else return redirect()->to(base_url());
    }
}