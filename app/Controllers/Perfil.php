<?php
namespace App\Controllers;

use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    public function actualizar()
    {
        if (!session()->get('logueado')) {
            return redirect()->to(base_url('login')); //solo verificamos q este logeado, los dos roles pueden
        }

        $esChofer = (session()->get('rol') === 'chofer');
        $reglas = [
            'telefono_whatsapp' => [  //para chofer si es oblig el telefono
                'rules'  => ($esChofer ? 'required' : 'permit_empty') . '|regex_match[/^549\d{10}$/]',
                'errors' => [
                    'required'    => 'El WhatsApp es obligatorio para choferes.',
                    'regex_match' => 'Formato inválido. Usá 549 + área sin 15 + número (13 dígitos, ej: 5493436123456).'
                ]
            ],
            'clave' => [
                'rules'  => 'permit_empty|min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*\d).+$/]',
                'errors' => [
                    'min_length'  => 'La nueva contraseña debe tener al menos 8 caracteres.',
                    'regex_match' => 'La nueva contraseña debe incluir 1 mayúscula y 1 número.'
                ]
            ]
        ];

        if (!$this->validate($reglas)) { //si no pasa la validacion, vuelve a la vista con los errores , o sea vuelve atras
            return redirect()->back()->with('error', reset($this->validator->getErrors()));
        }

        $wsp = trim((string) $this->request->getPost('telefono_whatsapp')); 
        $datos = ['telefono_whatsapp' => ($wsp !== '') ? $wsp : null]; //si el campo esta vacio, lo guardamos como null en la bbdd

        $nuevaClave = (string) $this->request->getPost('clave');
        if ($nuevaClave !== '') { //si no pones clave nueva, no se cambia la clave
            $datos['clave'] = password_hash($nuevaClave, PASSWORD_DEFAULT);
        }

        (new UsuarioModel())->update(session()->get('id_usuario'), $datos); //actualizamos el usuario con los datos nuevos
        session()->set('telefono_whatsapp', $datos['telefono_whatsapp']);

        return redirect()->back()->with('exito', 'Perfil actualizado correctamente.');
    }
}