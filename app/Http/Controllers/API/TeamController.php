<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Team;
use App\Image;

class TeamController extends Controller
{
    public function show($id){
        $team = Team::find($id);
        if ($team){
            return response()->json([
                'mensagem'=>'encontrado',
                'dados'=>'$team',
                'código'=>'200',
            ], 200);
        }
        return response()->json([
            'mensagem'=>'não encontrado'
        ]);
    }

    public function store (Request $request)
    {
        $validator = Validator:: make($request->all(),[
            'name'=> 'required|string',
            'office'=>'required|string',
            'Twitter'=>'required|string',
            'Facebook'=>'required|string',
            'Instagram'=>'required|string',
            'Linkedin'=>'required|string',
            'image_id'=>'nullable|integer'
        ]);

        if ($validator->fails()){
            return response()->json([
             'dados'=>$validator->errors(),
             'mensagem'=> 'Campos preenchidos incorretamente',
             'códigos'=>'400' 
         ], 400);
        }

        $image = Image::find($request->image_id);
        if (!$image)
            return response()->json([
            'mensagem'=>'A imagem com o id passado não existe',
            'código'=>'404'
        ], 404);

        $team = Team::create($request->all());
        if (!$team)
            return response()->json([
                'mensagem'=>'Ocorreu um erro',
                'código'=>'500'
            ], 500);

            return response()->json([
            'dados'=> $team,
            'mensagem'=> 'Criado com sucesso',
            'código'=> '201'
        ], 201);
    }

    public function update (Request $request, $id)
    {
        $validator = Validator::make ($request->all(),[
            'name'=> 'nullable|string',
            'office'=>'nullable|string',
            'Twitter'=>'required|URL',
            'Facebook'=>'required|URL',
            'Instagram'=>'required|URL',
            'Linkedin'=>'required|URL',
            'image_id'=>'nullable|integer'
        ]);

        if($validator->fails()){
            return response()->json([
            'mensagem'=> 'Campos preenchidos incorretamente',
            'códigos'=> '400'
        ], 400);  
        
        if ($request->has('image_id'))
        {
            $image = Image::find($request->image_id);
            if (!$image)
                return response()->json([
                    'mensagem'=>'A imagem com o id passado não existe',
                    'código'=>'404'
                ], 404);
        }    

        $team = Team::find($request->id);
        if (!$team){
            return response()->json([
                'mensagem'=>'Não encontramos este membro',
                'códigos'=>'404'
            ], 404);
        }
        /**Ele funciona como um if.
         * Ele testa uma condição (o primeiro operando), se ela for verdadeira,
         * o resultado da operação é o primeiro valor (após o ?, o segundo operando), 
         * se ela for falsa,
         * então o resultado é o segundo valor (depois do :, o terceiro operando).*/
    }
        $team->name = $request->name ? $request->name : $team->name;
        $team->office = $request->office ? $request->office : $team->office;
        $team->Twitter = $request->Twitter ? $request->Twitter : $team->Twitter;
        $team->Facebook = $request->Facebook? $request->Facebook : $team->Facebook;
        $team->Instagram = $request->Instagram? $request->Instagram : $team->Instagram;
        $team->Linkedin = $request->Linkedin ? $request->Linkedin : $team->Linkedin;
        $team->image = $request->image ? $request->image : $team->image;
        $team->save();
        
            return response()->json([
                'mensagem'=>'Dados atualizados com sucesso',
                'códigos'=>'200'
        ], 200);
        }
    public function destroy ($id)
    {
        $team = Team::find ($id);
            if (!$team)
            {
             return response()->json([
                 'mensagem'=>'Não encontramos esse membro',
                 'código'=>'404'
        ], 404);
    }
        $team->delete();
            return response()->json([
                'mensagem'=>'membro deletado com sucesso'
        ], 200);
 }
    public function index (Request $request)
    {
        $team = Team::all();
            if($team)
                return response()->json([
                    'dados'=>$team,
                    'mensagem'=>'Membros existentes',
                    'código'=>'200'
        ], 200);
    
                return response()->json([
                    'mensagem'=>'Não há membros',
                    'códigos'=>'404'
         ], 404);
}
}