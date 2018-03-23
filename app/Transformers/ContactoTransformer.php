<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Contacto;
class ContactoTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Contacto $contacto)
    {
        return  [
            'identifier' => (int)$contacto->id,
            'name' => (string)$contacto->name,
            'email' => (string)$contacto->email,
            'message' => (string)$contacto->message,
            'creationDate' => $contacto->created_at,
            'lastChange' => $contacto->updated_at,
 
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'message' => 'message',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            
        ];

        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'identifier',
            'name' => 'name',
            'email' => 'email',
            'message' => 'message',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            
            'link'=>[
                'rel' => 'self',
                'href' => route('contacto.show',$contacto->id),
            ]
        ];

        return isset($attributes[$index]) ? $attributes[$index]: null;
    }
}
