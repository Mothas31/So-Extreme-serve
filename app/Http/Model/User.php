<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class User extends Model
{
  public static function selectUserContext($token){
    $client = new Client();
    $result = $client->request('GET',"https://auth.shadow-side.com/auth/realms/SoExtreme-ETI/protocol/openid-connect/userinfo", [
    'headers' => [
        'Authorization' => 'Bearer '.$token
        ]
    ]);
    $user = $result->getBody()->getContents();
    $email = json_decode($user)->email;
    $context = DB::select("select U.*, P.relname from public.user as U, pg_class as P where email= ? AND U.tableoid = P.oid", [$email]);
    if(sizeof($context)>0){
      $data = User::selectUserData($context[0]->relname, $context[0]->id);
      $data->relname = $context[0]->relname;
      return $data;
    } else {
      return json_decode($user);
    }
  }

  public static function selectUserData($userType, $userId){
    $data = DB::select('select * from public.'.$userType.' where id = ?', [$userId]);
    return $data[0];
  }

  public static function insertNewUser($userType, $data){
    $result = DB::insert('INSERT INTO public.'.$userType.'(
      email, firstname, lastname, avatar, inscriptiondate)
      VALUES (?, ?, ?, ?, ?)', 
      [$data['email'], $data['firstname'], $data['lastname'], $data['avatar'], $data['inscriptiondate']]
    );
    $id = DB::getPdo()->lastInsertId();
    return $id;
  }

  public static function upsert($data, $userType){
    if($userType == 'client'){
      $result = DB::update("UPDATE public.client SET
        email= ? , firstname= ? , lastname= ? , avatar= ?  WHERE id = ?", 
        [$data['email'], $data['firstname'], $data['lastname'], $data['avatar'], $data['id']]);
    }
    return $result;
  }
}