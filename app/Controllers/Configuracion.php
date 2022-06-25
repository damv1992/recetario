<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;

class Configuracion extends BaseController {

	public function __construct() {
		$this->configuraciones = new ConfiguracionModel();
	}

    public function validar() {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
			$nombre = $this->request->getPost('nombre');
			$icono = $this->request->getFile('icono');
			$logo = $this->request->getFile('logo');
			$configuracion = $this->configuraciones->first();
            $campos = ''; $mensajes = ''; $contador = 0;
            if (!$nombre) {
				$contador++; $campos .= 'nombrePagina,';
				$mensajes .= 'Es obligatorio que la página tenga un nombre,';
            }
            if (($icono == null) && (!$configuracion['IconoPagina'])) {
				$contador++; $campos .= 'iconoPagina,';
				$mensajes .= 'Debe subir un archivo que representará el ícono de la página,';
            } else if ($icono <> null) {
				$extension = $icono->getExtension();
                if ($extension <> 'png') {
					$contador++; $campos .= 'iconoPagina,';
					$mensajes .= 'El archivo debe ser imagen de tipo .png,';
				}
			}
            if (($logo == null) && (!$configuracion['LogoPagina'])) {
				$contador++; $campos .= 'logoPagina,';
				$mensajes .= 'Debe subir un archivo que representará el ícono de la página,';
            } else if ($logo <> null) {
				$extension = $logo->getExtension();
                if ($extension <> 'png' && $extension <> 'jpg') {
					$contador++; $campos .= 'logoPagina,';
					$mensajes .= 'El archivo debe ser imagen de tipo .png o .jpg,';
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
			$nombre = ucwords($this->request->getPost('nombre'));
			$frase = ucfirst($this->request->getPost('frase'));
			$icono = $this->request->getFile('icono');
			$logo = $this->request->getFile('logo');
			$sobre = $this->request->getPost('sobre');
			$configuracion = $this->configuraciones->first();
			if (!$configuracion) return 'danger';
			if ($icono <> null) $archivoIcono = $this->subirArchivo($icono, 'favicon');
			else $archivoIcono = $configuracion['IconoPagina'];
			if ($logo <> null) $archivoLogo = $this->subirArchivo($logo, 'logo');
			else $archivoLogo = $configuracion['LogoPagina'];
			$this->configuraciones->where([
				'CodigoConfiguracion' => 1,
			])->set([
				'NombrePagina' => $nombre,
				'FrasePagina' => $frase,
				'IconoPagina' => $archivoIcono,
				'LogoPagina' => $archivoLogo,
				'SobreNosotros' => $sobre
			])->update();
            return 'success';
        } else return 'danger';
    }

	public function subirArchivo($archivo, $nombre) {
		$ruta = "/GoodGames/assets/images/configuracion";
		$extension = $archivo->getExtension();
		if (file_exists($ruta.'/'.$nombre.'.'.$extension)) unlink('.'.$ruta.'/'.$nombre.'.'.$extension);
		if (($extension == 'png') && ($nombre == 'favicon')) {
			\Config\Services::image()
				->withFile($archivo)
				->resize(54, 54, false, 'auto')
				->save('.'.$ruta.'/'.$nombre.'.'.$extension);
		} else if ((($extension == 'png') || ($extension == 'jpg')) && ($nombre == 'logo')) {
			$archivo->move('.'.$ruta, $nombre.'.'.$extension);
			\Config\Services::image()
				->withFile($archivo)
				->resize(199, 51, true, 'height')
				->save('.'.$ruta.'/'.$nombre.'.'.$extension);
		} return $ruta.'/'.$nombre.'.'.$extension;
	}
}