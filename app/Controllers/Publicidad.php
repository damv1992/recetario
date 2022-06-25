<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\CarritoModel;

class Publicidad extends Home {	

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
			'titulo' => 'Publicidades'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/publicidad/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $hoy = date('Y-m-d');
            $publicidadess = $this->publicidades->orderBy('CodigoPublicidad DESC')->findAll();
            $cantidad = $this->publicidades->orderBy('CodigoPublicidad DESC')->countAllResults();
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($publicidadess as $publicidad) {
				$contador++;
				$acciones = "<form action='".site_url('publicidad/editar')."' method='post'>";
				$acciones .= "<input name='id' value='".$publicidad['CodigoPublicidad']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarPublicidad btn btn-danger' codigo='".$publicidad['CodigoPublicidad']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";
				$imagen = "<img src='".base_url().$publicidad['ImagenPublicidad']."' height='35'>";
				if ($publicidad['FechaHoraFin'] < date('Y-m-d'))
					$this->publicidades->where([
						'CodigoPublicidad' => $publicidad['CodigoPublicidad']
					])->set([
						'EstadoPublicidad' => 0
					])->update();
				if ($publicidad['EstadoPublicidad'] == 1) {
                    $colorEstado = "btn-success";
                    $textoEstado = "ACTIVO";
                    $estadoPublicidad = 1;
                } else {
                    $colorEstado = "btn-danger";
                    $textoEstado = "INACTIVO";
                    $estadoPublicidad = 0;
                }
				$estado = "<button class='btn ".$colorEstado." btn-xs btnCambiar' estado='".$estadoPublicidad."' codigo='".$publicidad['CodigoPublicidad']."'>".$textoEstado."</button>";
                if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $publicidad['Titular'] . '",
                        "' . $imagen . '",
                        "' . $estado . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $contador . '",
                        "' . $publicidad['Titular'] . '",
                        "' . $imagen . '",
                        "' . $estado . '",
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
			'titulo' => 'Nueva Publicidad'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/publicidad/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$titular = $this->request->getPost('titular');
			$descripcion = $this->request->getPost('descripcion');
			$enlace = $this->request->getPost('enlace');
			$inicio = $this->request->getPost('inicio');
			$fin = $this->request->getPost('fin');
			$imagen = $this->request->getFile('imagen');
            $campos = ''; $mensajes = ''; $contador = 0;
			$existe = $this->publicidades->where('Titular', $titular)->first();
			if (!$titular) {
				$contador++; $campos .= 'titularPublicidad,';
				$mensajes .= 'Este campo es obligatorio,';
			} else {
				if ($existe && $existe['CodigoPublicidad'] <> $id) {
					$contador++; $campos .= 'titularPublicidad,';
					$mensajes .= 'Ya existe este registro,';
				}
			}
			if (!$descripcion) {
				$contador++; $campos .= 'descripcionPublicidad,';
				$mensajes .= 'Este campo es obligatorio,';
			}
			if (!$enlace) {
				$contador++; $campos .= 'enlacePublicidad,';
				$mensajes .= 'Este campo es obligatorio,';
			}
			if (!$inicio) {
				$contador++; $campos .= 'fechaInicioPublicidad,';
				$mensajes .= 'Este campo es obligatorio,';
			}
			if (!$fin) {
				$contador++; $campos .= 'fechaFinPublicidad,';
				$mensajes .= 'Este campo es obligatorio,';
			}
			if ($inicio > $fin) {
				$contador++; $campos .= 'fechaFinPublicidad,';
				$mensajes .= 'La fecha de inicio debe ser antes que la final,';
			}
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
			$titular = ucfirst($this->request->getPost('titular'));
			$descripcion = $this->request->getPost('descripcion');
			$enlace = $this->request->getPost('enlace');
			$inicio = $this->request->getPost('inicio');
			$fin = $this->request->getPost('fin');
			$imagen = $this->request->getFile('imagen');
			if (($inicio <= date('Y-m-d') && ($fin >= date('Y-m-d') ))) $estado = 1;
			else $estado = 0;
			if ($id) {
				$existe = $this->publicidades->where('CodigoPublicidad', $id)->first();
				if ($imagen <> null) $archivo = $this->subirArchivo($id, $imagen);
				else $archivo = $existe['ImagenPublicidad'];
				$this->publicidades->where([
					'CodigoPublicidad' => $id
				])->set([
					'Titular' => $titular,
					'Descripcion' => $descripcion,
					'ImagenPublicidad' => $archivo,
					'EnlacePublicidad' => $enlace,
					'EstadoPublicidad' => $estado,
					'FechaHoraInicio' => $inicio,
					'FechaHoraFin' => $fin
				])->update();
			} else {
				$id = $this->generarId();
				$archivo = $this->subirArchivo($id, $imagen);
				$this->publicidades->insert([
					'CodigoPublicidad' => $id,
					'Titular' => $titular,
					'Descripcion' => $descripcion,
					'ImagenPublicidad' => $archivo,
					'EnlacePublicidad' => $enlace,
					'EstadoPublicidad' => $estado,
					'FechaHoraInicio' => $inicio,
					'FechaHoraFin' => $fin
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function estado() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $id = $this->request->getPost('id');
            $estado = $this->request->getPost('estado');
            if ($estado == 1) $estado = 0;
            else $estado = 1;
            $this->publicidades->where([
                'CodigoPublicidad' => $id
            ])->set([
                'EstadoPublicidad' => $estado
            ])->update();
            return "ok";
        } else return "error";
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$publicidad = $this->publicidades->where('CodigoPublicidad', $id)->first();
		$datos += [
			'titulo' => 'Modificar Publicidad',
			'publicidad' => $publicidad
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/publicidad/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function subirArchivo($nombre, $archivo) {
		$ruta = "/GoodGames/assets/images/publicidad";
		$extension = $archivo->getExtension();
		if (file_exists($ruta.'/'.$nombre.'.'.$extension)) unlink('.'.$ruta.'/'.$nombre.'.'.$extension);
		\Config\Services::image()
			->withFile($archivo)
			->resize(1080, 357, false, 'auto')
			->save('.'.$ruta.'/'.$nombre.'.'.$extension);
		return $ruta.'/'.$nombre.'.'.$extension;
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->publicidades->where('CodigoPublicidad', $id)->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->publicidades->where('CodigoPublicidad', $id)->first()) return $id;
        }
	}
}