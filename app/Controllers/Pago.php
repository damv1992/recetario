<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

class Pago extends Home {	

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
			'titulo' => 'Métodos de Pago'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/pago/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $metodosPago = $this->pagos->findAll();
            $cantidad = $this->pagos->countAllResults();
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($metodosPago as $metodoPago) {
				$contador++;
				$acciones = "<form action='".site_url('pago/editar')."' method='post'>";
				$acciones .= "<input name='id' value='".$metodoPago['CodigoMetodo']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarMetodoPago btn btn-danger' codigo='".$metodoPago['CodigoMetodo']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";
				$imagen = "<img src='".base_url().$metodoPago['ImagenMetodo']."' width='35' height='35'>";
                if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $imagen . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $imagen . '",
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
			'titulo' => 'Nuevo Método de Pago'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/pago/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$imagen = $this->request->getFile('imagen');
            $campos = ''; $mensajes = ''; $contador = 0;
            if ($imagen == null) {
				if (!$id) {
					$contador++; $campos .= 'verImagen,';
					$mensajes .= 'Debe subir un archivo al registro,';
				}
            } else {
				$extension = $imagen->getExtension();
                if (($extension <> 'jpg') && ($extension <> 'jpeg') && ($extension <> 'png')) {
					$contador++; $campos .= 'verImagen,';
					$mensajes .= 'El archivo debe ser de tipo imagen (jpg o jpeg o png),';
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
			$imagen = $this->request->getFile('imagen');
			if ($id) {
				$archivo = $this->subirArchivo($id, $imagen);
				$this->pagos->where([
					'CodigoMetodo' => $id
				])->set([
					'ImagenMetodo' => $archivo
				])->update();
			} else {
				$id = $this->generarId();
				$archivo = $this->subirArchivo($id, $imagen);
				$this->pagos->insert([
					'CodigoMetodo' => $id,
					'ImagenMetodo' => $archivo
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$metodoPago = $this->pagos->where('CodigoMetodo', $id)->first();
		$datos += [
			'titulo' => 'Modificar Método de Pago',
			'metodoPago' => $metodoPago
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/pago/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function subirArchivo($nombre, $archivo) {
		$ruta = "/GoodGames/assets/images/pago";
		$extension = $archivo->getExtension();
		if (file_exists($ruta.'/'.$nombre.'.'.$extension)) unlink('.'.$ruta.'/'.$nombre.'.'.$extension);
		\Config\Services::image()
			->withFile($archivo)
			->resize(100, 100, false, 'auto')
			->save('.'.$ruta.'/'.$nombre.'.'.$extension);
		return $ruta.'/'.$nombre.'.'.$extension;
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->pagos->where('CodigoMetodo', $id)->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->pagos->where('CodigoMetodo', $id)->first()) return $id;
        }
	}
}