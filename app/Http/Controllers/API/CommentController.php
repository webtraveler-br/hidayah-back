<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Comment;
use App\Image;

class CommentController extends Controller
{
    public function store(Request $request){
      $validator = Validator::make($request->all(), [
        'nome' => ['required', 'string'],
        'emprego' => ['required', 'string'],
        'comentario' => ['required', 'string'],
        'imagem_id' => ['required', 'integer']
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors(),
          'code' => '400'
        ], 400);
      }

      $image = Image::find($request->imagem_id);
      if (!$image) {
        return response()->json([
          'error' => ['Imagem não encontrada'],
          'code' => '404'
        ], 404);
      }

      $comment = Comment::create($request->all());
      return response()->json([
        'message' => ['Depoimento criado com sucesso'],
        'code' => '201'
      ], 201);
    }

    public function index(){
      $comments = Comment::all();

      return response()->json([
        'message' => $comments,
        'code' => '200'
      ], 200);
    }

    public function show($id){
      $comment = Comment::find($id);
      if (!$comment) {
        return response()->json([
          'error' => ['Depoimento não encontrado'],
          'code' => '404'
        ], 404);
      }

      return response()->json([
        'message' => $comment,
        'code' => '200'
      ], 200);
    }

    public function destroy($id){
      $comment = Comment::find($id);
      if (!$comment) {
        return response()->json([
          'error' => ['Depoimento não encontrado'],
          'code' => '404'
        ], 404);
      }

      $comment->delete();
      return response()->json([
        'message' => ['Depoimento deletado com sucesso'],
        'code' => '200'
      ], 200);
    }

    public function update(Request $request){
      $validator = Validator::make($request->all(), [
        'nome' => ['required_without:emprego,comentario,imagem', 'string'],
        'emprego' => ['required_without:nome,comentario,imagem', 'string'],
        'comentario' => ['required_without:emprego,nome,imagem', 'string'],
        'imagem' => ['required_without:emprego,comentario,nome', 'integer']
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors(),
          'code' => '400'
        ], 400);
      }

      $comment = Comment::find($id);
      if (!$comment) {
        return response()->json([
          'error' => ['Depoimento não encontrado'],
          'code' => '404'
        ], 404);
      }

      if (isset($request->imagem_id)) {
        $image = Image::find($request->imagem);
        if (!$image) {
          return response()->json([
            'error' => ['Imagem não encontrada'],
            'code' => '404'
          ], 404);
        }
      }

      $comment->nome = $request->nome;
      $comment->emprego = $request->emprego;
      $comment->comentario = $request->comentario;
      $comment->imagem()->associate($image);
      $comment->save();
      return response()->json([
        'message' => ['Comentário atualizado com sucesso'],
        'code' => '201'
      ], 201);
    }
}
