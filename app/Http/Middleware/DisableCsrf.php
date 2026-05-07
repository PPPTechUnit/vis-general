<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class DisableCsrf extends Middleware
{
        protected $except = ['*']; // disable CSRF for all routes

}
