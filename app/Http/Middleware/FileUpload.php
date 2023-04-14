<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FileUpload
{

    /**
     * to check document is valid, parameter valid by extentions
     */
    private function isDocumentValid()
    {
    }

    /**
     * to check image is valid, parameter valid by extentions
     */
    private function isImageValid()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $name is a name input from request name
     * @param string $model | (image | document)
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $name, string $model)
    {
        return $next($request);
    }
}
