<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Image;

class Team extends Model
{
    /* Esta variável define os campos que podemos mudar*/
    protected $fillable = ['name','office','Facebook','Twitter','Instagram','Linkedin','image_id'];
    protected $with = ['image'];
    
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
