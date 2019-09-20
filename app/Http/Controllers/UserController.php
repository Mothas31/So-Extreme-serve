<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Http\Model\User;
use App\Http\Model\Evaluation;
use App\Http\Model\Communicate;
use App\Http\Model\Keywords;

use App\Providers\Keycloak\Keycloak as Keycloak;

class UserController extends Controller
{
  public $keycloaRoot;
  public $keycloakRegister;

  public function __construct() {
    $confRoot = [
      'authServerUrl'         => env('AUTH_URL'),
      'realm'                 => env('REALM'),
      'clientId'              => env('CLIENT_ID'),
      'clientSecret'          => env('SECRET_KEY'),
      'redirectUri'           => env('REDIRECT_URI')
    ];
    $this->keycloakRoot = new Keycloak($confRoot);
  }

  public function check(){
    $response = new Response('OK', 200);
    return $response;
  }

  public function login(Request $request){
    try {
      $keycloakLocal = new Keycloak($confRoot = [
        'authServerUrl'         => env('AUTH_URL'),
        'realm'                 => env('REALM'),
        'clientId'              => env('CLIENT_ID'),
        'clientSecret'          => env('SECRET_KEY'),
        'redirectUri'           => env('REDIRECT_URI').'/'.$request->input('target')
      ]);
      $accessToken = $keycloakLocal->getAccessToken('authorization_code', ['code' => $request->input('token')]);
      $user = $keycloakLocal->getResourceOwner($accessToken);
      
      $dataUser = User::selectUserContext($accessToken);
      $response = new Response(json_encode($dataUser), 200);

      $response->headers->set("Set-Cookie", 'authorization='.$accessToken->getToken().'; httpOnly; path=/; expires='.$accessToken->getExpires());
      return $response;
    } catch (Exception $e) {
      exit('Failed to get access token: '.$e->getMessage());
    }
  }

  public function logout(){
    $logoutUrl = $this->keycloakRoot->getLogoutUrl();
    $response = new Response($logoutUrl, 200);
    return $response;
  }

  public function getUserContext(Request $request){
    $context = $request->header('X-User');
    $data = User::selectUserContext($request->cookie('authorization'));
    $response = new Response(json_encode($data), 200);
    return $response;
  }

  public function postNewClient(Request $request){
    $pload = $request->all();
    $idInserted = User::insertNewUser("client", $pload['user']);
    if($idInserted){
      $user = User::selectUserData("client", $idInserted);
      $response = new Response(json_encode($user), 200);
    } else {
      $reponse = false;
    }
    return $response;
  }

  public function upsertUser(Request $request){
    $context = $request->header('X-User');
    $pload = $request->all();
    $inserted = User::upsert($pload['user'], $context->relname);
    if($inserted){
      $user = User::selectUserData("client", $pload['user']['id']);
      $response = new Response(json_encode($user), 200);
    }
    return $response;
  }
}