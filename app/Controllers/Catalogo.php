<?php

namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\RedesSocialesModel;
use App\Models\PublicidadModel;
use App\Models\PlataformaModel;
use App\Models\MetodosPagoModel;
use App\Models\UsuarioModel;
use App\Models\TiposModel;
use App\Models\GenerosModel;

use App\Models\ProductosModel;
use App\Models\CarritoModel;

class Catalogo extends Home {

	public function __construct() {
        $this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();
        $this->session->start();
		$this->configuraciones = new ConfiguracionModel();
        $this->sociales = new RedesSocialesModel();
        $this->publicidades = new PublicidadModel();
        $this->plataformas = new PlataformaModel();
        $this->pagos = new MetodosPagoModel();
        $this->usuarios = new UsuarioModel();
        $this->tipos = new TiposModel();
        $this->generos = new GenerosModel();
        
        $this->productos = new ProductosModel();
        $this->carritos = new CarritoModel();
	}

	public function index() {
		$datos = $this->datosPrincipales();
		$plataforma = $this->request->getGet('plataforma');
		$datos += [
			'titulo' => 'Catálogo',
            'plataforma' => $plataforma
		];
		return view('catalogo', $datos);
	}

    public function filtrarProductos() {
        $busqueda = $this->request->getPost('busqueda');
        $precioMin = $this->request->getPost('precioMin');
        $precioMax = $this->request->getPost('precioMax');
        $orden = $this->request->getPost('orden');
        $limite = $this->request->getPost('limite');
        $pagina = $this->request->getPost('pagina');
        $plataforma = $this->request->getPost('plataforma');
        $tipo = $this->request->getPost('tipo');
        $genero = $this->request->getPost('genero');
        $cantidad = $this->cantidadFiltradoProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero);
        $output = array(
            'botonesFiltroProductos' => $this->generarBotonesFiltroProductos($plataforma, $tipo, $genero),
            'contadorFiltroProductos' => $cantidad,
            'resultadosFiltroProductos' => $this->resultadoFiltroProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero, $orden, $limite, $pagina),
            'paginasFiltroProductos' => $this->generarBotonesPaginacion($cantidad, $pagina)
        );
        echo json_encode($output);
    }

    public function generarBotonesFiltroProductos($plataforma, $tipo, $genero) {
        $output = '<div class="nk-widget nk-widget-highlighted">
            <h4 class="nk-widget-title"><span class="text-main-1">Plataformas</span></h4>
            <div class="nk-widget-content">
                <ul class="nk-widget-categories">';
        if (!$plataforma) {
            $output .= '<input id="txtPlataforma" type="hidden">';
            $plataformass = $this->plataformas->orderBy('NombrePlataforma', 'ASC')->findAll();
            foreach ($plataformass as $plataforma) {
                $icono = '<img src="'.$plataforma['IconoPlataforma'].'" height="20">';
                $output .= '<li><a onclick="plataforma('.$plataforma['CodigoPlataforma'].');" href="#">'.$icono.' '.$plataforma['NombrePlataforma'].'</a></li>';
            }
        } else {
            $output .= '<input id="txtPlataforma" value="'.$plataforma.'" type="hidden">';
            $plataforma = $this->plataformas->where('CodigoPlataforma', $plataforma)->first();
            $icono = '<img src="'.$plataforma['IconoPlataforma'].'" height="20">';
            $output .= '<li><a onclick="limpiarPlataforma();" href="#">'.$icono.' '.$plataforma['NombrePlataforma'].'<i class="fa fa-times float-right"></i></a></li>';
        }
                $output .= '</ul>
            </div>
        </div>';

        $output .= '<div class="nk-widget nk-widget-highlighted">
            <h4 class="nk-widget-title"><span class="text-main-1">Tipos</span></h4>
            <div class="nk-widget-content">
                <ul class="nk-widget-categories">';
        if (!$tipo) {
            $output .= '<input id="txtTipo" type="hidden">';
            $tiposs = $this->tipos->orderBy('NombreTipo', 'ASC')->findAll();
            foreach ($tiposs as $tipo) {
                $output .= '<li><a onclick="tipo('.$tipo['CodigoTipo'].');" href="#">'.' '.$tipo['NombreTipo'].'</a></li>';
            }
        } else {
            $output .= '<input id="txtTipo" value="'.$tipo.'" type="hidden">';
            $tipo = $this->tipos->where('CodigoTipo', $tipo)->first();
            $output .= '<li><a onclick="limpiarTipo();" href="#">'.' '.$tipo['NombreTipo'].'<i class="fa fa-times float-right"></i></a></li>';
        }
                $output .= '</ul>
            </div>
        </div>';

        $output .= '<div class="nk-widget nk-widget-highlighted">
            <h4 class="nk-widget-title"><span class="text-main-1">Generos</span></h4>
            <div class="nk-widget-content">
                <ul class="nk-widget-categories">';
        if (!$genero) {
            $output .= '<input id="txtGenero" type="hidden">';
            $geneross = $this->generos->orderBy('NombreGenero', 'ASC')->findAll();
            foreach ($geneross as $genero) {
                $output .= '<li><a onclick="genero('.$genero['CodigoGenero'].');" href="#">'.' '.$genero['NombreGenero'].'</a></li>';
            }
        } else {
            $output .= '<input id="txtGenero" value="'.$genero.'" type="hidden">';
            $genero = $this->generos->where('CodigoGenero', $genero)->first();
            $output .= '<li><a onclick="limpiarGenero();" href="#">'.' '.$genero['NombreGenero'].'<i class="fa fa-times float-right"></i></a></li>';
        }
                $output .= '</ul>
            </div>
        </div>';

        return $output;
    }

    public function consultaFiltradoProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero) {
        $query = "SELECT * FROM productos ";
        $query .= "LEFT JOIN plataformas ON plataformas.CodigoPlataforma = productos.Plataforma ";
        $query .= "LEFT JOIN tipos ON tipos.CodigoTipo = productos.Tipo ";
        $query .= "LEFT JOIN generosproducto ON generosproducto.Producto = productos.CodigoProducto ";
        $query .= "LEFT JOIN generos ON generos.CodigoGenero = generosproducto.Genero ";
        $query .= "WHERE productos.Precio BETWEEN ".$precioMin." AND ".$precioMax." ";
        if ($busqueda) $query .= "AND productos.NombreProducto LIKE '%".$busqueda."%' ";
        if ($plataforma) $query .= "AND productos.Plataforma = ".$plataforma." ";
        if ($tipo) $query .= "AND productos.Tipo = ".$tipo." ";
        if ($genero) $query .= "AND generosproducto.Genero = ".$genero." ";
        $query .= "GROUP BY productos.CodigoProducto ";
        return $query;
    }
    function cantidadFiltradoProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero) {
        $query = $this->consultaFiltradoProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero);
        $data = $this->db->query($query);
        return $data->getNumRows();
    }

    public function resultadoFiltroProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero, $orden, $limite, $pagina) {
        $query = $this->consultaFiltradoProductos($busqueda, $precioMin, $precioMax, $plataforma, $tipo, $genero);
        if ($orden) $query .= "ORDER BY ".$orden." ASC ";
        if ($pagina) {
            $fin = $pagina*10;
            $inicio = $fin-10;
            $query .= 'LIMIT '.$inicio.', ' . $fin;
        }
        $data = $this->db->query($query);
        $output = '';
        if ($data->getNumRows() > 0) {
            foreach ($data->getResultArray() as $producto) {
                $output .= '<div class="col-md-6">
                    <div class="nk-product-cat">
                        <a class="nk-product-image" href="'.site_url('producto/detalle/'.$producto['CodigoProducto']).'">
                            <img src="'.$producto['FotoProducto'].'">
                        </a>
                        <div class="nk-product-cont">
                            <h3 class="nk-product-title h5"><a href="'.site_url('producto/detalle/'.$producto['CodigoProducto']).'">'.$producto['NombreProducto'].'</a></h3>
                            <div class="nk-gap-1"></div>
                            <div class="nk-product-price">Bs. '.$producto['Precio'].'</div>
                            <div class="nk-gap-1"></div>
                            <a href="#" onclick="añadirCarrito('.$producto['CodigoProducto'].');" class="nk-btn nk-btn-rounded nk-btn-color-dark-3 nk-btn-hover-color-main-1">Añadir al carrito</a>
                        </div>
                    </div>
                </div>';
            }
        } return $output;
    }

    public function generarBotonesPaginacion($cantidad, $pagina) {
        if ($pagina) $output = '<input id="txtPagina" value="'.$pagina.'" type="hidden">';
        else $output = '<input id="txtPagina" value="1" type="hidden">';

        $output .= '<div class="nk-pagination nk-pagination-center">';

        if ($cantidad > 0) {
            $cantidad = intval($cantidad / 10) + 1;
            
            if (($cantidad % 10) == 0) {
                if (($cantidad > 10) && ($pagina > 10)) {
                    $cantidad = $cantidad % 10;
                    $output .= '<a href="#Producto" onclick="PaginaAnteriorProducto('.$cantidad.')" class="nk-pagination-prev">
                        <span class="ion-ios-arrow-back"></span>
                    </a>';
                }
            }

            for ($i = 1; $i <= $cantidad; $i++) {
                if ($i <= 10) {
                    $output .= '<nav>';
                    if ($pagina == $i || (($pagina == "") && ($i == 1)))
                        $output .= '<a id="btnPaginaProducto" onclick="paginaProducto(this, '.$i.')" class="nk-pagination-current" href="#">'.$i.'</a>';
                    else
                        $output .= '<a id="btnPaginaProducto" onclick="paginaProducto(this, '.$i.')" href="#">'.$i.'</a>';
                    $output .= '</nav>';
                }
            }

            if (($cantidad%10) == 0) {
                if ($cantidad > 10) {
                    $cantidad = $cantidad % 10;
                    $output .= '<a href="#Producto" onclick="PaginaSiguienteProducto('.$cantidad.')" class="nk-pagination-next">
                        <span class="ion-ios-arrow-forward"></span>
                    </a>';
                }
            }
        }
        $output .= '</div>';
        return $output;
    }

    public function agregarCarrito() {
        $ip = gethostbyname('www.google.com');
        //$ip = $_SERVER['REMOTE_ADDR'];
        $codigo = $this->request->getPost('codigo');
        $carrito = $this->carritos->where([
            'DireccionIp' => $ip,
            'Producto' => $codigo
        ])->first();
        if (!$carrito) {
            $this->carritos->insert([
                'DireccionIp' => $ip,
                'Producto' => $codigo
            ]);
        }
        $cantidad = $this->carritos->where('DireccionIp', $ip)->countAllResults();
        $json = array(
            'cantidad' => $cantidad
        );
        return json_encode($json);
    }

    public function retirarCarrito() {
        $ip = gethostbyname('www.google.com');
        //$ip = $_SERVER['REMOTE_ADDR'];
        $codigo = $this->request->getPost('codigo');
        $this->carritos->where([
            'DireccionIp' => $ip,
            'Producto' => $codigo
        ])->delete();
        $cantidad = $this->carritos->where('DireccionIp', $ip)->countAllResults();
        $json = array(
            'cantidad' => $cantidad
        );
        return json_encode($json);
    }

    public function carritoInfo() {
        $ip = gethostbyname('www.google.com');
        //$ip = $_SERVER['REMOTE_ADDR'];
        $carritoss = $this->carritos->where('DireccionIp', $ip)->findAll();
        $cantidad = count($carritoss);
        $total = 0;
        $output = '<span class="nk-cart-toggle">
            <span class="fa fa-shopping-cart"></span>
            <span class="nk-badge">'.$cantidad.'</span>
        </span>
        <div class="nk-cart-dropdown">';
        foreach ($carritoss as $carrito) {
            $producto = $this->productos->where('CodigoProducto', $carrito['Producto'])->first();
            $plataforma = $this->plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
            $total += $producto['Precio'];
            $detalle = site_url('producto/detalle/'.$producto['CodigoProducto']);
            $output .= '<div class="nk-widget-post">
                <a href="'.$detalle.'" class="nk-post-image">
                    <img src="'.$producto['FotoProducto'].'">
                </a>
                <h3 class="nk-post-title">
                    <a href="#" onclick="retirarCarrito('.$producto['CodigoProducto'].');" class="nk-cart-remove-item">
                        <span class="ion-android-close"></span>
                    </a>
                    <a href="'.$detalle.'">'.$producto['NombreProducto'].' ('.$plataforma['NombrePlataforma'].')</a>
                </h3>
                <div class="nk-product-price">Bs. '.$producto['Precio'].'</div>
            </div>';
        }
        if ($cantidad > 0) {
            if ($cantidad > 1) $pedir = 'Pedir productos (Bs. '.$total.')';
            else $pedir = 'Pedir producto';
            $output .= '<div class="nk-gap-2"></div>
            <div class="text-center">
                <a href="'.site_url('catalogo/carrito').'"
                    class="nk-btn nk-btn-rounded nk-btn-color-main-1 nk-btn-hover-color-white">'.$pedir.'</a>
            </div>';
        } else $output .= '<div class="text-center color-white">Tu carrito está vacío</div>';
        $output .= '</div>';
        $json = array(
            'html' => $output
        );
        return json_encode($json);
    }

    public function carrito() {
		$datos = $this->datosPrincipales();
        $ip = gethostbyname('www.google.com');
        //$ip = $_SERVER['REMOTE_ADDR'];
        $carritoss = $this->carritos->where('DireccionIp', $ip)->findAll();
		$datos += [
			'titulo' => 'Pedir',
            'carritos' => 'carritoss'
		];
        if (count($carritoss) > 0) return view('carrito', $datos);
        else return redirect()->to(base_url());
	}

    public function pedir() {
        $ip = gethostbyname('www.google.com');
        //$ip = $_SERVER['REMOTE_ADDR'];
        $carritoss = $this->carritos->where('DireccionIp', $ip)->findAll();
        $configuracion = $this->configuraciones->first();
        $cantidad = count($carritoss);
        if ($cantidad > 0) {
            $mensaje = 'Saludos '.$configuracion['NombrePagina'].'.\n\n';
            if ($cantidad > 1) $cantidad = 'los siguientes productos';
            else $cantidad = 'el siguiente producto';
            $mensaje .= 'Solicito '.$cantidad.':\n';
            foreach ($carritoss as $carrito) {
                $producto = $this->productos->where('CodigoProducto', $carrito['Producto'])->first();
                $plataforma = $this->plataformas->where('CodigoPlataforma', $producto['Plataforma'])->first();
                $mensaje .= ' - '.$producto['NombreProducto'].' ('.$plataforma['NombrePlataforma'].')\n';
            }
            $mensaje .= '\nEstaría encantado de tener respuesta de mi pedido.';
            $mensaje = str_replace(' ', '%20', $mensaje);
            $mensaje = str_replace('\n', '%0D%0A', $mensaje);
            $this->carritos->where('DireccionIp', $ip)->delete();
            return redirect()->to('https://api.whatsapp.com/send?phone=59173354006&text='.$mensaje);
        } else return redirect()->to(base_url());
	}
}
