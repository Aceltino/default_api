<?php

namespace App\Middleware;

use App\Core\Response;
use App\Core\Auth;
use App\Core\Request;

class AuthMiddleware extends \App\Core\Middleware
{
    // Rotas públicas que não requerem autenticação
    protected array $publicRoutes = [
        'POST:/auth/login',
        'GET:/posts',
        'GET:/posts/categories',
        'GET:/posts/category/eventos', //Entendo que bastava um pregMatch para deixar isso mais dinâmico 'GET:/posts/category/{category}'
        'GET:/posts/category/noticias',
        'GET:/posts/category/projectos',
        'GET:/posts/category/voluntariado',
    ];

    public function handle($request, $next)
    {
        // Verifica se a rota atual está nas rotas públicas
        if ($this->isPublicRoute($request)) {
            return $this->next($request, $next);
        }

        // Verifica autenticação para rotas protegidas
        if (!Auth::check()) {
            Response::error('Unauthorized', 401);
        }

        return $this->next($request, $next);
    }

    /**
     * Verifica se a rota atual está na lista de rotas públicas
     */
    protected function isPublicRoute(Request $request): bool
    {
        $currentRoute = $request->getMethod() . ':' . $request->getPath();

        return in_array($currentRoute, $this->publicRoutes);
    }
}
