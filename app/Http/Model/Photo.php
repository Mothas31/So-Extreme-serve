<?php

namespace App\Http\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
  
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'photos';

    public static function sendPhotoToBdd($imageName, $photoPath, $comment){
        $result = DB::insert('INSERT INTO public.photos(
          activity_id, comment,  photoName , photoPath, user_id , photoMime)
          VALUES (?, ?, ?, ?, ?, ?)', 
          [1, $comment, $imageName, $photoPath, 1, "photoMime"]
        );
        
      }

    
}