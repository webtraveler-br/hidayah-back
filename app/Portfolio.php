<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Image;
use App\Category;

class Portfolio extends Model
{
    protected $fillable = ['titulo', 'link', 'imagem_id'];
    protected $with = ['imagem'];

    public function imagem(){
      return $this->belongsTo(Image::class);
    }

    public function categorias(){
      return $this->belongsToMany(Category::class, 'category_portfolio', 'portifolio_id', 'categoria_id');
    }
}
