<?php
namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuario extends Home {	

	public function __construct() {
		$this->usuarios = new UsuarioModel();
        $this->session = \Config\Services::session();
        $this->session->start();
	}

    public function generarCodigoUsuario() {
        $codigo = 0;
        while (true) {
            $codigo++;
            if (!$this->usuarios->where('CodigoUsuario', $codigo)->first()) return $codigo;
        }
    }

    /*public function CrearUsuarioAjax()
    {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $codigo = $this->generarCodigoUsuario();
			$email = $this->request->getPost('emailcrear');
            //$password = password_hash($this->request->getPost('passwordcrear'), PASSWORD_DEFAULT);
            $password = $this->request->getPost('passwordcrear');
			$nombre = $this->request->getPost('nombrecrear');
			$direccion = $this->request->getPost('direccioncrear');
			$telefono = $this->request->getPost('telefonocrear');
			$empresa = $this->request->getPost('empresacrear');
			$pagina = $this->request->getPost('paginacrear');
			$rol = $this->request->getPost('rolcrear');
			$fecha = date('Y-m-d H:i:s');
            $verificarEmail = $this->usuarios->where('Email', $email)->first();
            if ($nombre == "") return "nombre";
            if ($direccion == "") return "direccion";
            if ($telefono == "") return "telefono";
            if ($rol == "") return "rol";
            if ($verificarEmail['Email'] <> "") return "existe";
            $resultado = $this->enviarCorreoValidacion($email, $nombre);
            if ($resultado == "ok") {
                $this->usuarios->insert([
                    'CodigoUsuario' => $codigo,
                    'Email' => $email,
                    'Password' => $password,
                    'NombreCompleto' => $nombre,
                    'Direccion' => $direccion,
                    'Telefono' => $telefono,
                    'Empresa' => $empresa,
                    'Pagina' => $pagina,
                    'EmailValidado' => 0,
                    'RolAsignado' => $rol,
                    'FechaHoraRegistro' => $fecha
                ]);
            }
            return $resultado;
        } else return "error";
    }*/

    /*public function confirmar()
    {
		$email = $this->request->getGet('activar');
        $usuario = $this->usuarios->where([
            'Email' => $email,
            'EmailValidado' => 0
        ])->first();
        if ($usuario['CodigoUsuario'] <> "") {
            // Validado
            $this->usuarios->whereIn('Email', [$email])->set([
                'EmailValidado' => 1
            ])->update();
            $this->session->set($usuario);
            return "<script>alert('Cuenta confirmada.'); location.href = '".base_url()."';</script>";
        }
        // No valida
        else return redirect()->to(base_url());
    }*/

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
    }

	/*public function ActualizarUsuarioAjax()
    {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $codigo = $this->session->get('CodigoUsuario');
            $password = $this->request->getPost('passwordactualizar');
			$nombre = $this->request->getPost('nombreactualizar');
			$direccion = $this->request->getPost('direccionactualizar');
			$telefono = $this->request->getPost('telefonoactualizar');
			$empresa = $this->request->getPost('empresaactualizar');
			$pagina = $this->request->getPost('paginaactualizar');
			$rol = $this->request->getPost('rolactualizar');
            if ($codigo == "") return "sesion";
            if ($nombre == "") return "nombre";
            if ($direccion == "") return "direccion";
            if ($telefono == "") return "telefono";
            if ($rol == "") return "rol";
            if ($password == "") {
                $this->usuarios->where([
                    'CodigoUsuario' => $codigo
                ])->set([
                    'NombreCompleto' => $nombre,
                    'Direccion' => $direccion,
                    'Telefono' => $telefono,
                    'Empresa' => $empresa,
                    'Pagina' => $pagina,
                    'RolAsignado' => $rol
                ])->update();
            } else {
                $this->usuarios->where([
                    'CodigoUsuario' => $codigo
                ])->set([
                    'Password' => $password,
                    'NombreCompleto' => $nombre,
                    'Direccion' => $direccion,
                    'Telefono' => $telefono,
                    'Empresa' => $empresa,
                    'Pagina' => $pagina,
                    'RolAsignado' => $rol
                ])->update();
            }
            $nuevaSesion = $this->usuarios->where('CodigoUsuario', $codigo)->first();
            $this->session->set($nuevaSesion);
            return "ok";
        } else return "error";
    }*/

    /*public function OlvidoContraseñaAjax()
    {
        if ($this->request->isAjax() && $this->request->getMethod() == "post") {
            $email = $this->request->getPost('emailRcñ');
            if ($email == "") return "vacio";
            $correo = $this->usuarios->where('Email', $email)->first();
            if ($correo['CodigoUsuario'] == "") return "no_registrado";
            $resultado = $this->enviarCorreoRecuperacionCña($correo['NombreCompleto'], $correo['Email'], $correo['Password']);
            return $resultado;
        } else return "error";
    }*/

    /*public function enviarCorreoRecuperacionCña($nombre, $email, $contraseña)
    {
        $asunto = "Restablecimiento de contraseña | BUEN GANADO";
        $mensaje = "<p>Hola, ".$nombre.".</p><br>";
        $mensaje .= "<p>Alguien ha solicitado un restablecimiento de la contraseña utilizando su dirección de correo electrónico. <b>".$email."</b></p><br>";
        $mensaje .= "<p>La contraseña con la que se registró en el sitio es: <b>".$contraseña."</b></p><br>";
        $mensaje .= "<p>Se le recomienda cambiar su contraseña una vez ingrese a la página <a href='".base_url()."'>BUEN GANADO</a>, en la opción: Mis datos</p><br>";
        $mensaje .= "<p>Si necesita ayuda, pongase en contacto por favor con el administrador del sitio,</p><br>";
        $mensaje .= "<p>BUEN GANADO</p><br>";

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        // Additional headers
        $headers[] = 'To: '.$nombre.' <'.$email.'>';
        $headers[] = 'From: BUEN GANADO <informacion@buenganado.com>';
        // If Mail it
        if (mail($email, $asunto, $mensaje, implode("\r\n", $headers))) return "ok";
		else return "fallo";
    }*/
}
?>