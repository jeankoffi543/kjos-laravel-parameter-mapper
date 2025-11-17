<?php

namespace Kjos\ParameterMapper\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kjos\ParameterMapper\Support\ParameterMapper;

class MapRequestParameters
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // GET parameters
        $query = ParameterMapper::apply($request->query());
        $request->query->replace($query);

        // POST, PUT, PATCH parameters
        $input = $request->all();

        // Si la requête est JSON, merge avec le JSON body
        if ($request->isJson()) {
            $input = array_merge($input, $request->json()->all());
        }

        $mapped = ParameterMapper::apply($input);

        // Remplacer les paramètres du request
        $request->replace($mapped);

        // Si JSON, mettre à jour le contenu JSON
        if ($request->isJson()) {
            $request->json()->replace($mapped);
        }

        return $next($request);
    }
}
