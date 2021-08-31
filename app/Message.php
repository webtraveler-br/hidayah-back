<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  // variavel protegida para fazer adições massivas ao banco de dados
  // que normalmente é protegido
    protected $fillable = ['name', 'email', 'subject', 'message'];
}
