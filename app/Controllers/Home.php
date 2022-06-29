<?php

namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\CategoriasModel;
use App\Models\RecetasModel;
use App\Models\IngredientesModel;
use App\Models\PreparacionesModel;

class Home extends BaseController {

	public function __construct() {
        $this->db = \Config\Database::connect();
		$this->session = \Config\Services::session();
        $this->session->start();
        $this->configuraciones = new ConfiguracionModel();
        $this->categorias = new CategoriasModel();
        $this->recetas = new RecetasModel();
        $this->ingredientes = new IngredientesModel();
        $this->preparaciones = new PreparacionesModel();
	}

	public function datosPrincipales() {
		$configuracion = $this->configuraciones->first();
		$datos = [
			'configuracion' => $configuracion
		];
		return $datos;
	}

	public function index() {
		$configuracion = $this->configuraciones->first();
		if (!$configuracion['IdConfiguracion']) $this->generarDatosPagina();
		//$productoss = $this->productos->orderBy('NombreProducto')->findAll(4);
		$datos = $this->datosPrincipales();
		$datos += [
			'titulo' => 'Inicio'
		];
		return view('index', $datos);
	}

	public function generarDatosPagina() {
		$this->configuraciones->insert([
			'IdConfiguracion' => 1,
			'NombrePagina' => 'Recetas Bolivianas',
			'LogoPagina' => '/RecipeBook/images/pagina/logo.svg',
			'IconoPagina' => '/RecipeBook/images/pagina/favicon.png',
			'SobreNosotros' => '<p>Un libro de recetas es uno que usas a diario y lo que en nuestra familia llamamos "un libro vivo":
				Un libro que usas todo el tiempo, no solo lo lees una vez y lo tiras al estante.
				Las recetas son por naturaleza derivadas y están destinadas a ser compartidas; así es como mejoran, se modifican, cómo se forman nuevas ideas.</p>',
			'Usuario' => 'memesis',
			'Contraseña' => 'memesis'
		]);
	}

	public function filtrarRecetas() {
        $categoria = $this->request->getPost('categoria');
        $pagina = $this->request->getPost('pagina');
        $cantidad = $this->cantidadFiltradoRecetas($categoria);
        $output = array(
            'botonesFiltroRecetas' => $this->generarBotonesFiltroRecetas($categoria),
            'resultadosFiltroRecetas' => $this->resultadoFiltroRecetas($categoria, $pagina),
            'paginasFiltroRecetas' => $this->generarBotonesPaginacion($cantidad, $pagina)
        );
        echo json_encode($output);
    }

    public function generarBotonesFiltroRecetas($cat) {
		$output = '<div class="row">
			<div class="col-12"><h2>Categorías</h2></div>
		</div>
		<div class="row wow zoomIn">';
        if (!$cat) $output .= '<input id="txtCategoria" type="hidden">';
        else $output .= '<input id="txtCategoria" value="'.$cat.'" type="hidden">';
		$categoriass = $this->categorias->orderBy('NombreCategoria', 'ASC')->findAll();
		foreach ($categoriass as $categoria) {
			if ($cat <> $categoria) {
				$botonCategoria = 'categoria('.$categoria['IdCategoria'].');';
				$colorCategoria = 'category-item';
			}
			else {
				$botonCategoria = 'limpiarCategoria();';
				$colorCategoria = 'category-item2';
			}
			$output .= '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
				<a onclick="'.$botonCategoria.'" href="#">
					<div class="'.$colorCategoria.' text-center">
						<img src="'.$categoria['IconoCategoria'].'" alt="'.$categoria['NombreCategoria'].'" />
						<br />'.$categoria['NombreCategoria'].'
					</div>
				</a>
			</div>';
		}
        $output .= '</div>';

		$output .= '<div class="row wow zoomIn">
			<div class="col-12 text-center show-all">
				<a onclick="limpiarTodo();" href="#"><div class="category-item text-center">
            		<i class="fa fa-cutlery fa-2x" aria-hidden="true"></i>
            		<br />
            		Mostrar Todo
				</div></a>
			</div>
		</div>';
		return $output;
    }

    public function consultaFiltradoRecetas($categoria) {
        $query = "SELECT * FROM recetas ";
        $query .= "LEFT JOIN categorias ON recetas.Categoria = categorias.IdCategoria ";
        $query .= "WHERE recetas.IdReceta > 0 ";
        if ($categoria) $query .= "AND recetas.Categoria = ".$categoria." ";
        $query .= "GROUP BY recetas.IdReceta ";
		$query .= "ORDER BY recetas.NombreReceta ASC ";
        return $query;
    }

	function cantidadFiltradoRecetas($categoria) {
        $query = $this->consultaFiltradoRecetas($categoria);
        $data = $this->db->query($query);
        return $data->getNumRows();
    }

    public function resultadoFiltroRecetas($categoria, $pagina) {
        $query = $this->consultaFiltradoRecetas($categoria);
        if ($pagina) {
            $fin = $pagina*9;
            $inicio = $fin-9;
            $query .= 'LIMIT '.$inicio.', ' . $fin;
        }
        $data = $this->db->query($query);
        $output = '';
        if ($data->getNumRows() > 0) {
            foreach ($data->getResultArray() as $receta) {
				$output .= '<div class="col-lg-4 col-md-6 col-sm-12 wow fadeIn">
					<div class="recipe-item text-center">
						<a href="'.site_url('home/receta/'.$receta['IdReceta']).'">
							<img src="'.$receta['FotoReceta'].'" alt="'.$receta['NombreReceta'].'" />
							<br /><h3>'.$receta['NombreReceta'].'</h3>
						</a>
					</div>
				</div>';
            }
        } return $output;
    }

    public function generarBotonesPaginacion($cantidad, $pagina) {
        if ($pagina) $output = '<input id="txtPagina" value="'.$pagina.'" type="hidden">';
        else $output = '<input id="txtPagina" value="1" type="hidden">';

        $output .= '<center>';

        if ($cantidad > 0) {
            $cantidad = intval($cantidad / 10) + 1;
            
            if (($cantidad % 10) == 0) {
                if (($cantidad > 10) && ($pagina > 10)) {
                    $cantidad = $cantidad % 10;
                    $output .= '<a href="#Receta" onclick="PaginaAnteriorReceta('.$cantidad.')" class="col-1 category-item">
                        <span class="ion-ios-arrow-back"></span>
                    </a>';
                }
            }

            for ($i = 1; $i <= $cantidad; $i++) {
                if ($i <= 10) {
                    if ($pagina == $i || (($pagina == "") && ($i == 1)))
                        $output .= '<a id="btnPaginaReceta" onclick="paginaReceta(this, '.$i.')" class="col-1 category-item" href="#">'.$i.'</a>';
                    else
                        $output .= '<a id="btnPaginaReceta" onclick="paginaReceta(this, '.$i.')" class="col-1 category-item2" href="#">'.$i.'</a>';
                }
            }

            if (($cantidad%10) == 0) {
                if ($cantidad > 10) {
                    $cantidad = $cantidad % 10;
                    $output .= '<a href="#Receta" onclick="PaginaSiguienteReceta('.$cantidad.')" class="col-1 category-item">
                        <span class="ion-ios-arrow-forward"></span>
                    </a>';
                }
            }
        }
        $output .= '</center>';
        return $output;
    }

	public function receta($receta) {
		$datos = $this->datosPrincipales();
		$ingredientess = $this->ingredientes->where('Receta', $receta)->findAll();
		$preparacioness = $this->preparaciones->where('Receta', $receta)->orderBy('PasoNumero ASC')->findAll();
		$receta = $this->recetas->where('IdReceta', $receta)->first();
		$datos += [
			'titulo' => $receta['NombreReceta'],
			'receta' => $receta,
			'ingredientes' => $ingredientess,
			'preparaciones' => $preparacioness
		];
		return view('receta', $datos);
	}

	/*public function __construct() {
		$this->usuarios = new UsuarioModel();
        $this->session = \Config\Services::session();
        $this->session->start();
	}

    public function LoginUsuarioAjax() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $usuario = $this->usuarios->where('Usuario', $username)->first();
            if (!$usuario['Usuario']) return "no_existe";
            if ($usuario['Contrasena'] <> $password) return "incorrecto";
            $this->session->set($usuario);
            return "ok";
        } else return "error";
    }

    public function desconectar() {
        $this->session->destroy();
        return redirect()->to(base_url());
    }*/
}
