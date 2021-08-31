<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use App\Portfolio;

class Image extends Model
{
    protected $fillable = ['caminho', 'descricao'];

    public function comments()
    {
      return $this->hasMany(Comment::class, 'imagem_id');
    }
    public function portfolios()
    {
      return $this->hasMany(Portfolio::class, 'imagem_id');
    }
}
