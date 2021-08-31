<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Image;

class Comment extends Model
{
    protected $fillable = ['nome', 'emprego', 'comentario', 'imagem_id'];
    protected $with = ['imagem'];

    public function imagem(){
      return $this->belongsTo(Image::class);
    }
}
