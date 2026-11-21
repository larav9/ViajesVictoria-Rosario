<?php
namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('login/login');
    }

    public function validarLogin()
    {
        $usuarioModel = new UsuarioModel();

        // traemos el user y contra del post, y lo limpiamos de espacios y lo casteamos a string
        $userPost = trim((string) $this->request->getPost('usuario'));
        $passPost = (string) $this->request->getPost('clave');

        $user = $usuarioModel->where('usuario', $userPost)->first(); //buscamos al primero q coincida

        if ($user && password_verify($passPost, $user['clave'])) { //si existe y la clave coincide , se inicia la sesión
            session()->set([
                'id_usuario'        => $user['id'],
                'nombre'            => $user['nombre'] . ' ' . $user['apellido'],
                'usuario'           => $user['usuario'],
                'telefono_whatsapp' => $user['telefono_whatsapp'],
                'rol'               => $user['rol'],
                'logueado'          => true
            ]);
            
            $urlDestino = ($user['rol'] === 'chofer') ? base_url('chofer/gestion') : base_url('panel'); //si es chofer lo mandamos a la gestion de viajes, si no al panel de usuario

            return $this->response->setJSON([
                'success'  => true,
                'mensaje'  => '¡Bienvenido/a, ' . ucwords($user['nombre']) . '! Redirigiendo...',
                'redirect' => $urlDestino
            ]); //el response que lo detecta el js y hace la redirección
        }

        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'Usuario o clave incorrectos.',
            'usuario' => $userPost,
            'token'   => csrf_hash()
        ]); //el response por el falso
    }

    public function registro()
    {
        return view('login/registro');
    }

    public function registrar()
    {
        $esChofer = ($this->request->getPost('tipo_cuenta') === 'chofer');
        //en el chofer pide telefono, en pasajero no es obligatorio, por eso lo usamos para validar
        $reglas = [
            'clave' => [
                'rules'  => 'required|min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*\d).+$/]', //q tenga al menos 8 caracteres, 1 mayuscula y 1 numero
                'errors' => [
                    'required'    => 'Ingresá una contraseña.',
                    'min_length'  => 'La contraseña debe tener al menos 8 caracteres.',
                    'regex_match' => 'La contraseña debe incluir al menos 1 mayúscula y 1 número.'
                ]
            ],
            'telefono_whatsapp' => [
                'rules'  => ($esChofer ? 'required' : 'permit_empty') . '|regex_match[/^549\d{10}$/]', // sin puntos y con 549 al principio, sin caract
                'errors' => [
                    'required'    => 'El número de WhatsApp es obligatorio para choferes.',
                    'regex_match' => 'Formato inválido. Usá 549 + área sin 15 + número (13 dígitos, ej: 5493436123456).'
                ]
            ]
        ];

        if (!$this->validate($reglas)) {
            $errores = $this->validator->getErrors();
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => reset($errores),
                'token'   => csrf_hash()
            ]);
        }

        $usuarioModel = new UsuarioModel();
        $datos = $this->request->getPost();

        if ($usuarioModel->where('usuario', $datos['usuario'])->first()) {// si ya existe el usario 
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Ese nombre de usuario ya está en uso. Elegí otro.',
                'token'   => csrf_hash()
            ]);
        }

        $datos['clave'] = password_hash($datos['clave'], PASSWORD_DEFAULT);
        $datos['rol']   = $esChofer ? 'chofer' : 'pasajero';

        if (!$usuarioModel->insert($datos)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo crear la cuenta. Revisá los datos ingresados.',
                'token'   => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'success'  => true,
            'mensaje'  => '¡Cuenta creada con éxito, ' . ucwords($datos['nombre']) . '! Te llevamos al inicio de sesión...',
            'redirect' => base_url('login') //lo lleva desp de crear la cuenta
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('panel')); //si cerras sesion no te devuelve al login s ino al panel q puede ver cualquiera, ahi de cualquier forma pordes ir al login o registro
    }
}