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
use App\Models\AlquileresModel;
use App\Models\GenerosModel;
use App\Models\GenerosAlquilerModel;

class Alquiler extends Home {

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
        $this->productos = new AlquileresModel();
        $this->generos = new GenerosModel();
        $this->generosproductos = new GenerosAlquilerModel();
	}

	public function index() {
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Alquiler de cuenta'
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/alquiler/index', $datos);
		else return redirect()->to(base_url());
    }

	public function listar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $productoss = $this->productos->orderBy('NombreProducto ASC')->findAll();
            $cantidad = count($productoss);
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($productoss as $producto) {
				$contador++;
				$acciones = "<form action='".site_url('alquiler/editar')."' method='post'>";
                $acciones .= "<a href='".site_url('alquiler/generos/'.$producto['CodigoProducto'])."' class='btn btn-success'><i class='fa fa-arrow-right'></i></a>";
				$acciones .= "<input name='id' value='".$producto['CodigoProducto']."' type='number' style='display: none;'>";
                $acciones .= "<button class='btn btn-warning'><i class='fa fa-pencil'></i></button>";
                $acciones .= "<a class='btnBorrarProducto btn btn-danger' codigo='".$producto['CodigoProducto']."'><i class='fa fa-trash'></i></a>";
				$acciones .= "</form>";

				$imagen = "<img src='".$producto['FotoProducto']."' height='35'>";
                $plataforma = $this->plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
				$icono = "<img src='".$plataforma['IconoPlataforma']."' height='35'>";
				if ($contador < $cantidad) {
                    $datosJson .= '[
                        "' . $imagen . '",
                        "' . $producto['NombreProducto'] . '",
                        "' . $producto['Precio']." Bs." . '",
                        "' . $icono . '",
                        "' . $acciones . '"
                    ],';
                } else {
                    $datosJson .= '[
                        "' . $imagen . '",
                        "' . $producto['NombreProducto'] . '",
                        "' . $producto['Precio']." Bs." . '",
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
        $plataformass = $this->plataformas->orderBy('NombrePlataforma ASC')->findAll();
        $tiposs = $this->tipos->orderBy('NombreTipo ASC')->findAll();
		$datos += [
			'titulo' => 'Agregar juego a la cuenta',
            'plataformas' => $plataformass,
            'tipos' => $tiposs
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/alquiler/formulario', $datos);
		else return redirect()->to(base_url());
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$producto = $this->request->getPost('producto');
			$descripcion = $this->request->getPost('descripcion');
			$imagen = $this->request->getPost('imagen');
			$precio = $this->request->getPost('precio');
			$plataforma = $this->request->getPost('plataforma');
			$tipo = $this->request->getPost('tipo');
            $campos = ''; $mensajes = ''; $contador = 0;
			$existe = $this->productos->where([
                'NombreProducto' => $producto,
                'Plataforma' => $plataforma,
                'Tipo' => $tipo
            ])->first();
			if (!$producto) {
				$contador++; $campos .= 'nombreProducto,';
				$mensajes .= 'Este campo es obligatorio,';
			} else {
				if ($existe && $existe['CodigoProducto'] <> $id) {
					$contador++; $campos .= 'nombreProducto,';
					$mensajes .= 'Ya existe este registro,';
				}
			}
			if (!$descripcion) {
				$contador++; $campos .= 'descripcionProducto,';
				$mensajes .= 'Este campo es obligatorio,';
			}
			if (!$imagen) {
				$contador++; $campos .= 'imagenProducto,';
				$mensajes .= 'Este campo es obligatorio,';
			}
			if (!$plataforma) {
				$contador++; $campos .= 'plataformaProducto,';
				$mensajes .= 'Debe elegir la plataforma,';
			}
			if (!$tipo) {
				$contador++; $campos .= 'tipoProducto,';
				$mensajes .= 'Debe elegir el tipo de producto,';
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
			$producto = ucwords($this->request->getPost('producto'));
			$descripcion = $this->request->getPost('descripcion');
			$imagen = $this->request->getPost('imagen');
			$precio = $this->request->getPost('precio');
			$plataforma = $this->request->getPost('plataforma');
			$tipo = $this->request->getPost('tipo');
			if ($id) {
				$propio = $this->productos->where('CodigoProducto', $id)->first();
				if ($propio['Precio'] <> $precio) $precio *= 6.97;
				$this->productos->where([
					'CodigoProducto' => $id
				])->set([
					'NombreProducto' => $producto,
					'Descripcion' => $descripcion,
					'FotoProducto' => $imagen,
					'Precio' => $precio,
					'Plataforma' => $plataforma,
					'Tipo' => $tipo
				])->update();
			} else {
				$id = $this->generarId();
				$precio *= 6.97;
				$this->productos->insert([
					'CodigoProducto' => $id,
					'NombreProducto' => $producto,
					'Descripcion' => $descripcion,
					'FotoProducto' => $imagen,
					'Precio' => $precio,
					'Plataforma' => $plataforma,
					'Tipo' => $tipo
				]);
			}
            return 'success';
        } else return 'danger';
    }

	public function editar() {
		$datos = $this->datosPrincipales();
		$id = $this->request->getPost('id');
		$producto = $this->productos->where('CodigoProducto', $id)->first();
        $plataformass = $this->plataformas->orderBy('NombrePlataforma ASC')->findAll();
        $tiposs = $this->tipos->orderBy('NombreTipo ASC')->findAll();
		$datos += [
			'titulo' => 'Modificar juego de la cuenta',
			'producto' => $producto,
            'plataformas' => $plataformass,
            'tipos' => $tiposs
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/alquiler/formulario', $datos);
		else return redirect()->to(base_url());
	}

	public function borrar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
            if (!$id) return "error";
			if ($this->productos->where('CodigoProducto', $id)->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function generarId() {
        $id = 0;
        while (true) {
            $id++;
            if (!$this->productos->where('CodigoProducto', $id)->first()) return $id;
        }
	}

    public function generos($producto) {
		$datos = $this->datosPrincipales();
        $producto = $this->productos->where('CodigoProducto', $producto)->first();
        $plataforma = $this->plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
        $generosProducto = $this->generosproductos->where('Producto', $producto['CodigoProducto'])->findAll();
		$datos += [
			'titulo' => 'Generos asignados',
            'producto' => $producto,
            'plataforma' => $plataforma,
            'generosProducto' => $generosProducto
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/alquiler/generos', $datos);
		else return redirect()->to(base_url());
	}

    public function listarGeneros($producto) {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $generosproductoss = $this->generosproductos->where('Producto', $producto)->findAll();
            $cantidad = count($generosproductoss);
            $datosJson = '{"data": [';
			$contador = 0;
            foreach ($generosproductoss as $generoProducto) {
				$contador++;
                $acciones = "<a class='btnBorrarGeneroProducto btn btn-danger' genero='".$generoProducto['Producto']."'><i class='fa fa-trash'></i></a>";

                $genero = $this->generos->where('CodigoGenero', $generoProducto['Genero'])->first();
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

    public function asignar($producto) {
		$datos = $this->datosPrincipales();
        $producto = $this->productos->where('CodigoProducto', $producto)->first();
        $plataforma = $this->plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
        $geneross = $this->generos->orderBy('NombreGenero ASC')->findAll();
        $generosProducto = $this->generosproductos->where('Producto', $producto['CodigoProducto'])->findAll();
		$datos += [
			'titulo' => 'Asignar género',
            'producto' => $producto,
            'plataforma' => $plataforma,
            'generos' => $geneross,
            'generosProducto' => $generosProducto
		];
		if ($this->session->get('RolAsignado') == 'Administrador') return view('administracion/alquiler/asignar', $datos);
		else return redirect()->to(base_url());
	}

    public function validarGenero() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$genero = $this->request->getPost('genero');
            $campos = ''; $mensajes = ''; $contador = 0;
			$existe = $this->generosproductos->where([
                'Producto' => $id,
                'Genero' => $genero
            ])->first();
			if (!$id) {
				$contador++; $campos .= 'generoProducto,';
				$mensajes .= 'Error,';
			}
			if (!$genero) {
				$contador++; $campos .= 'generoProducto,';
				$mensajes .= 'Debe seleccionar un genero,';
			} else {
				if ($existe && ($existe['Producto'] <> $id) && ($existe['Genero'] <> $genero)) {
					$contador++; $campos .= 'nombreProducto,';
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

	public function guardarGenero() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$id = $this->request->getPost('id');
			$genero = $this->request->getPost('genero');
            $this->generosproductos->insert([
                'Producto' => $id,
                'Genero' => $genero
            ]);
            return 'success';
        } else return 'danger';
    }

    public function borrarGenero($id) {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$genero = $this->request->getPost('genero');
            if (!$id || !$genero) return "error";
			if ($this->generosproductos->where([
                'Producto' => $id,
                'Genero' => $genero
            ])->delete())
				return "ok";
			else return "uso";
        } else return "error";
	}

	public function detalle($id) {
		$datos = $this->datosPrincipales();
		$producto = $this->productos->where('CodigoProducto', $id)->first();
		$plataforma = $this->plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
		$generosProducto = $this->generosproductos
			->join('generos', 'generosalquiler.Genero=generos.CodigoGenero')
			->where('generosalquiler.Producto', $producto['CodigoProducto'])
			->orderBy('generos.NombreGenero ASC')
			->findAll();
		$datos += [
			'titulo' => $producto['NombreProducto'],
			'producto' => $producto,
			'generos' => $generosProducto,
			'plataforma' => $plataforma
		];
		return view('administracion/alquiler/detalle', $datos);
	}
}