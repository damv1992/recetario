<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

use App\Models\GenerosModel;

class Genero extends Home {	

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
		
        $this->generos = new GenerosModel();
	}

	public function index() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Generos'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/genero/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $geneross = $this->generos->orderBy('NombreGenero ASC')->findAll();
            $cantidad = count($geneross);
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($geneross as $genero) {
				$contador++;
				$acciones = "<form action='".site_url('genero/editar')."' method='post'>";
				$acciones .= "<input name='id' value='".$genero['CodigoGenero']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarGenero btn btn-danger' codigo='".$genero['CodigoGenero']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";
                if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $genero['NombreGenero'] . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $genero['NombreGenero'] . '",
                        "' . $acciones . '"
                    ]';
                }
            }
            $datosJson .= ']}';
            return $datosJson;
        } else {
            $datosJson = '{"data": [';
            $datosJson .= ']}';
            return $datosJson;
        }
    }

	public function nuevo() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Nuevo género'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/genero/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$genero = $this->request->getPost('genero');
			$existe = $this->generos->where('NombreGenero', $genero)->first();
            $campos = ''; $mensajes = ''; $contador = 0;
            if (!$genero) {
				$contador++; $campos .= 'nombreGenero,';
				$mensajes .= 'Este campo es obligatorio,';
            } else {
				if ($existe && $existe['CodigoGenero'] <> $id) {
					$contador++; $campos .= 'nombreGenero,';
					$mensajes .= 'Ya existe este registro,';
				}
			}
            $json = array(
                'contador' => $contador,
                'mensajes' => $mensajes,
                'campos' => $campos
            );
            return json_encode($json);
        } else return 'error';
    }

	public function guardar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$genero = ucfirst($this->request->getPost('genero'));
			if ($id)
				$this->generos->where([
					'CodigoGenero' => $id
				])->set([
					'NombreGenero' => $genero
				])->update();
			else {
				$id = $this->generarId();
				$this->generos->insert([
					'CodigoGenero' => $id,
					'NombreGenero' => $genero
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$genero = $this->generos->where('CodigoGenero', $id)->first();
		$datos += [
			'titulo' => 'Modificar género',
			'genero' => $genero
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/genero/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->generos->where('CodigoGenero', $id)->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->generos->where('CodigoGenero', $id)->first()) return $id;
        }
	}
}