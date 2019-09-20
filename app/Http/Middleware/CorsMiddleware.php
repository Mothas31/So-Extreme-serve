<?php
namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $response = $next($request);
    $IlluminateResponse = 'Illuminate\Http\Response';
    $SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
    $headers = [
      'Access-Control-Allow-Origin' => '*', // TODO replace with mobile and web apps urls eg:env('REDIRECT_URI'),
      'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
      'Access-Control-Allow-Headers' => 'Access-Control-Allow-Headers, Content-type, Access-Control-Allow-Credentials',
      'Access-Control-Allow-Credentials' => 'true',
    ];
    
    if($response instanceof $IlluminateResponse) {
      foreach ($headers as $key => $value) {
        $response->header($key, $value);
      }
      return $response;
    }
    
    if($response instanceof $SymfonyResopnse) {
      foreach ($headers as $key => $value) {
        $response->headers->set($key, $value);
      }
      return $response;
    }
    
    return $response;
  }
}

