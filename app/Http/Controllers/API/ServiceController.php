<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Service;

class ServiceController extends Controller
{
    public function show($id){
        $service = Service::find($id);
        if ($service){
            return response()->json([
                'mensagem'=>'encontrado',
                'dados'=> $service,
                'código'=>'200'
            ], 200);
        }
        return response()->json([
            'mensagem'=>'não encontrado'
        ]);
    }
    public function store (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'=> 'required|string',
            'description'=> 'required|string',
            'icon'=> 'required|string'
        ]);
        if($validator->fails()){
            return response()->json([
                'dados'=> $validator->errors(),
                'mensagem'=> 'campos preenchidos incorretamente :p',
                'códigos'=> '400'
            ], 400);
        }
        $service = Service::create($request->all());
        if(!$service){
            return response()->json([
                'mensagem'=> 'server error',
                'códigos'=> '500'
            ], 500);
        }
        return response()->json([
            'dados' => $service,
            'mensagem' => 'Serviço criado com sucesso :p',
            'código' => '201'
        ], 201);
    }
    public function update (Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'title'=> 'nullable|string',
            'description'=> 'nullable|string',
            'icon'=> 'nullable|string'
        ]);
        if($validator->fails()){
            return response()-> json([
                'mensagem'=> 'server error',
                'códigos'=> '500'
                ], 500);
        }
        $service = Service::find ($id);
        if (!$service){
            return response()->json([
                'mensagem'=>['serviço não encontrado'],
                'código'=>'500'
            ], 500);
        }
        $service->title = $request->title ? $request->title : $service->title;
        $service->description= $request->description ? $request->description : $service->description;
        $service->icon= $request->icon ? $request->icon : $service->icon;
        $service->save();

        return response()->json([
            'mensagem'=>'serviço atualizado com sucesso',
            'código'=>'200'
        ], 200);
    }
    public function destroy (Request $request){
        $validator = Validator::make($request->all(),[
            'id'=> 'required|integer',
        ]);
        if ($validator->fails()){
            return response()->json([
                'mensagem'=>'erro de validação',
                'dados'=> $validator-> errors()
            ]);
        }
        $service= Service::find ($request->id);
        if(!$service){
            return response()->json([
                'mesangem'=>'serviço não encontrado',
                'código'=>'500'
            ], 500);
        }
        $service->delete();
        return response()->json([
            'mensagem'=>'serviço deletado com sucesso'
        ],200);
    }   
    public function index(){
        $service= Service::all();
        if($service){
            return response()->json([
                'dados'=>$service,
                'mensagem'=>'Serviços existentes',
                'códigos'=>'200'
            ], 200);
        }
        return response()->json([
            'mensagem'=>'Não há serviços'
        ]);
    }   
}