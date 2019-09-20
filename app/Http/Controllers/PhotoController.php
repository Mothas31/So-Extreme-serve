<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Http\Model\Evaluation;
use App\Http\Model\Communicate;
use App\Http\Model\Keywords;
use Illuminate\Support\Facades\Storage;
use App\Http\Model\Photo;

use App\Providers\Keycloak\Keycloak as Keycloak;

class PhotoController extends Controller
{

  public function postPhoto()
  {

  }

  public function getPhotoByActivity()
  {

  }

  public function store(Request $request)
  {
    

    //$photo = $request->file('photo');
    //$extension = $photo->getClientOriginalExtension();
    $b64_file = $request->get('photo');
    
    $b64_file = str_replace('data:image/jpeg;base64,', '', $b64_file);
    $b64_file = str_replace(' ', '+', $b64_file);
    $imageName = str_random(10).'.'.'jpg';

/*
    $b64_file = str_replace('data:image/png;base64,', '', $b64_file);
    $b64_file = str_replace(' ', '+', $b64_file);
    $imageName = str_random(10).'.'.'png';
*/


    $comment = $request->get('comment');
    Storage::disk('public')->put($imageName,  base64_decode($b64_file));
    


    $photoPath = 'storage/public/' . $imageName . 'jpg';

    $response = new Response('{"status": "OK photo"}', 200);
    return $response;
   

    //Photo::sendPhotoToBdd($imageName, $photoPath, $comment);

    }

    /** Example of File Upload */


    public function reponse()
    {
        $response = new Response('{"status": "OK photo"}', 200);
        return $response;
    }
  
    /*
    public function uploadFilePost(Request $request){
      $request->validate([
          'fileToUpload' => 'required|file|max:1024',
      ]);

      $fileName = "fileName".time().'.'.request()->fileToUpload->getClientOriginalExtension();

        $request->fileToUpload->storeAs('photos',$fileName);

        return back()
            ->with('success','You have successfully upload image.');

      }
    */
}