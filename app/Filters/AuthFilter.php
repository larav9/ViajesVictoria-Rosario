<?php


namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

// si o si la  sesión  debe estar iniciada. Si no la hay: a las páginas las manda al login y a las
// peticiones AJAX les responde con un JSON 401 (para que el JS pueda avisar).
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logueado')) {
            return;
        }

        if ($request instanceof IncomingRequest && $request->isAJAX()) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['success' => false, 'mensaje' => 'Tenés que iniciar sesión.']);
        }

        return redirect()->to(base_url('login'));
    }
//si esta logeado no hace nada, va pasa el filtro y los controladores pueden seguir ejecutandose normalmente 
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
