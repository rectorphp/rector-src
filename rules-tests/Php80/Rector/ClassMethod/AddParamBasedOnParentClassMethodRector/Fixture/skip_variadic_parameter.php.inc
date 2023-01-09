<?php

class TransformsRequest {
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

class Middleware extends TransformsRequest
{
    protected $attributes = [];

    public function handle($request, Closure $next, ...$attributes)
    {
        $this->attributes = $attributes; return $next($request);
    }
}
