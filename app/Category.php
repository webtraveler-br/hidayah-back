<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Portfolio;

class Category extends Model
{
    protected $fillable = ['nome'];

    public function portifolios(){
      return $this->belongsToMany(Portfolio::class, 'category_portfolio', 'categoria_id', 'portifolio_id');
    }
}
