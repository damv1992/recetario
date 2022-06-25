<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

class Plataforma extends Home {

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
			'titulo' => 'Plataformas'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/plataforma/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $plataformass = $this->plataformas->orderBy('NombrePlataforma ASC')->findAll();
            $cantidad = $this->plataformas->orderBy('NombrePlataforma ASC')->countAllResults();
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($plataformass as $plataforma) {
				$contador++;
				$acciones = "<form action='".site_url('plataforma/editar')."' method='post'>";
				$acciones .= "<input name='id' value='".$plataforma['CodigoPlataforma']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarPlataforma btn btn-danger' codigo='".$plataforma['CodigoPlataforma']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";
				$icono = "<img src='".$plataforma['IconoPlataforma']."' height='35'>";
                if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $plataforma['NombrePlataforma'] . '",
                        "' . $icono . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $plataforma['NombrePlataforma'] . '",
                        "' . $icono . '",
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
			'titulo' => 'Nueva Plataforma'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/plataforma/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$plataforma = $this->request->getPost('plataforma');
			$icono = $this->request->getPost('icono');
            $campos = ''; $mensajes = ''; $contador = 0;
			$existe = $this->plataformas->where('IconoPlataforma', $icono)->first();
			if (!$icono) {
				$contador++; $campos .= 'iconoPlataforma,';
				$mensajes .= 'Debe seleccionar una plataforma,';
			} else {
				if ($existe && $existe['CodigoPlataforma'] <> $id) {
					$contador++; $campos .= 'iconoPlataforma,';
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
			$plataforma = $this->request->getPost('plataforma');
			$icono = $this->request->getPost('icono');
			if ($id) {
				$this->plataformas->where([
					'CodigoPlataforma' => $id
				])->set([
					'NombrePlataforma' => $plataforma,
					'IconoPlataforma' => $icono
				])->update();
			} else {
				$id = $this->generarId();
				$this->plataformas->insert([
					'CodigoPlataforma' => $id,
					'NombrePlataforma' => $plataforma,
					'IconoPlataforma' => $icono
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$plataforma = $this->plataformas->where('CodigoPlataforma', $id)->first();
		$datos += [
			'titulo' => 'Modificar Plataforma',
			'plataforma' => $plataforma
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/plataforma/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->plataformas->where('CodigoPlataforma', $id)->delete()) return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->plataformas->where('CodigoPlataforma', $id)->first()) return $id;
        }
	}
}