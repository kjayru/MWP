<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\ContactoTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacto extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public $transformer = ContactoTransformer::class;
    protected $table = 'contactos';

    protected $fillable = ['firstname','email','message'];
}
