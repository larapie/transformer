<?php


namespace Larapie\Transformer\Tests\Classes;


use Illuminate\Database\Eloquent\Model;

class ARelationModel extends Model
{
    protected $guarded = [];


    public function relation(){
        $this->belongsTo()
    }
}