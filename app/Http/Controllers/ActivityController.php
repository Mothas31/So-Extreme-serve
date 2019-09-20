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


class ActivityController extends Controller
{

  public function getActivities(){
    $response = new Response('{"status": "OK activity"}', 200);
    return $response;
  }

}