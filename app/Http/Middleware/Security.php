<?php

namespace App\Http\Middleware;

use App\Exceptions\SecurityException;
use Closure;
use Illuminate\Support\Facades\Log;

class Security
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Headers
        // XSS Protection
        header("X-XSS-Protection: 1; mode=block");
        // Mask PHP
        header("X-Powered-By: None-Of-Your-Business");
        header("Server: A-Web-Server/-1.0");

        // Simple IDS
        $filters = new \Expose\FilterCollection();
        $filters->load();

        // create a log channel
        $logger = app('log');
        $manager = new \Expose\Manager($filters, $logger);

        $post = false; //initialize
        $data = $request->all();
        if (!empty($data)) {
            $manager->run($data);
            $post = true;
        }

        if ($manager->getImpact() > 7 && $post === true) {
            throw new SecurityException();
        }

        return $next($request);
    }
}
