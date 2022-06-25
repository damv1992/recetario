<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

class Social extends Home {	

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
			'titulo' => 'Redes Sociales'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/social/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $redesSociales = $this->sociales->orderBy('IconoSocial ASC')->findAll();
            $cantidad = $this->sociales->orderBy('IconoSocial ASC')->countAllResults();
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($redesSociales as $redSocial) {
				$contador++;
				$acciones = "<form action='".site_url('social/editar')."' method='post'>";
				$acciones .= "<input name='id' value='".$redSocial['CodigoSocial']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarRedSocial btn btn-danger' codigo='".$redSocial['CodigoSocial']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";
				$enlace = "<a href='".$redSocial['EnlaceSocial']."' target='_blank'>".$redSocial['EnlaceSocial']."</a>";
				$icono = "<i class='".$redSocial['IconoSocial']."'></i>";
                if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $icono . '",
                        "' . $enlace . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $icono . '",
                        "' . $enlace . '",
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
			'titulo' => 'Nueva Red Social'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/social/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$enlace = $this->request->getPost('enlace');
			$icono = $this->request->getPost('icono');
			$existe = $this->sociales->where('IconoSocial', $icono)->first();
            $campos = ''; $mensajes = ''; $contador = 0;
            if (!$enlace) {
				$contador++; $campos .= 'enlaceRedSocial,';
				$mensajes .= 'Es obligatorio que la red social rediriga a un sitio web,';
            }
            if (!$icono) {
				$contador++; $campos .= 'iconoRedSocial,';
				$mensajes .= 'Es obligatorio que la red social tenga un icono que lo identifique,';
            } else {
				if ($existe && $existe['CodigoSocial'] <> $id) {
					$contador++; $campos .= 'iconoRedSocial,';
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
			$enlace = $this->request->getPost('enlace');
			$icono = $this->request->getPost('icono');
			if ($id)
				$this->sociales->where([
					'CodigoSocial' => $id
				])->set([
					'EnlaceSocial' => $enlace,
					'IconoSocial' => $icono
				])->update();
			else {
				$id = $this->generarId();
				$this->sociales->insert([
					'CodigoSocial' => $id,
					'EnlaceSocial' => $enlace,
					'IconoSocial' => $icono
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$redSocial = $this->sociales->where('CodigoSocial', $id)->first();
		$datos += [
			'titulo' => 'Modificar Red Social',
			'redSocial' => $redSocial
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/social/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->sociales->where('CodigoSocial', $id)->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->sociales->where('CodigoSocial', $id)->first()) return $id;
        }
	}
}