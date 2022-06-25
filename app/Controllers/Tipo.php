<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

use App\Models\TiposModel;

class Tipo extends Home {	

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
		
        $this->tipos = new TiposModel();
	}

	public function index() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Tipos de productos'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/tipo/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $tiposs = $this->tipos->orderBy('NombreTipo ASC')->findAll();
            $cantidad = $this->tipos->orderBy('NombreTipo ASC')->countAllResults();
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($tiposs as $tipo) {
				$contador++;
				$acciones = "<form action='".site_url('tipo/editar')."' method='post'>";
				$acciones .= "<input name='id' value='".$tipo['CodigoTipo']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarTipo btn btn-danger' codigo='".$tipo['CodigoTipo']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";
                if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $tipo['NombreTipo'] . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $tipo['NombreTipo'] . '",
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
			'titulo' => 'Nuevo tipo de producto'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/tipo/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$tipo = $this->request->getPost('tipo');
			$existe = $this->tipos->where('NombreTipo', $tipo)->first();
            $campos = ''; $mensajes = ''; $contador = 0;
            if (!$tipo) {
				$contador++; $campos .= 'nombreTipo,';
				$mensajes .= 'Este campo es obligatorio,';
            } else {
				if ($existe && $existe['CodigoTipo'] <> $id) {
					$contador++; $campos .= 'nombreTipo,';
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
			$tipo = ucfirst($this->request->getPost('tipo'));
			if ($id)
				$this->tipos->where([
					'CodigoTipo' => $id
				])->set([
					'NombreTipo' => $tipo
				])->update();
			else {
				$id = $this->generarId();
				$this->tipos->insert([
					'CodigoTipo' => $id,
					'NombreTipo' => $tipo
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$tipo = $this->tipos->where('CodigoTipo', $id)->first();
		$datos += [
			'titulo' => 'Modificar tipo de producto',
			'tipo' => $tipo
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/tipo/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->tipos->where('CodigoTipo', $id)->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->tipos->where('CodigoTipo', $id)->first()) return $id;
        }
	}
}