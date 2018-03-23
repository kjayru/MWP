<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contacto;
use Illuminate\Support\Facades\View;
use App\Transformers\ContactoTransformer;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;


class ContactoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.input'. ContactoTransformer::class)->only(['store','update']);
        $this->middleware('scope:manage-contacts')->only(['create','show']);
    }


    public function index()
    {
        
        $contacto = Contacto::all();
        return $this->showAll($contacto);

  
    }


    public function show(Contacto $contacto)
    {
       
        return $this->showOne($contacto);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'email' => 'required|email|unique:users',
            'message'=> 'required'
        ];
        
        $this->validate($request, $rules);

         $contacto = new Contacto;
         $contacto->firstname = $request->firstname;
         $contacto->email = $request->email;
         $contacto->message = $request->message;

         $contacto->save();
        
         

         return $this->showOne($contacto,201);
    }


}
