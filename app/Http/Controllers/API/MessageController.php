<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Message;
use Validator;

class MessageController extends Controller
{
  // função para armazenamento de mensagens no contate-nos
  public function store(Request $request){
    $validator = Validator::make($request->all(), [
      'nome' => ['required', 'string'],
      'email' => ['required', 'string', 'email'],
      'assunto' => ['required', 'string'],
      'mensagem' => ['required', 'string']
    ]);

    if ($validator->fails()) {
      return response()->json([
        'error' => $validator->errors(),
        'code' => '400'
      ], 400);
    }

    $message = Message::create([
      'name' => $request->nome,
      'email' => $request->email,
      'subject' => $request->assunto,
      'message' => $request->mensagem
    ]);

    if (!$message) {
      return response()->json([
        'error' => ['Erro interno do server ao guardar informações'],
        'code' => '500'
      ], 500);
    }

    return response()->json([
      'message' => $message,
      'code' => '201'
    ], 201);
  }

// mostra todas as mensagens até então
  public function index(){
    $data = Message::all();
    return response()->json([
      'message' => $data,
      'code' => '200'
    ], 200);
  }

  public function show($id){
    $message = Message::find($id);
    if (!$message) {
      return response()->json([
        'message' => ['Mensagem não encontrada'],
        'code' => '404'
      ], 404);
    }

    return response()->json([
      'message' => $message,
      'code' => '200'
    ], 200);
  }

  public function destroy($id){
    $message = Message::find($id);
    if (!$message) {
      return response()->json([
        'message' => ['Mensagem não encontrada'],
        'code' => '404'
      ], 404);
    }

    $message->delete();
    return response()->json([
      'message' => ['Mensagem deletada com sucesso'],
      'code' => '200'
    ], 200);
  }

}
