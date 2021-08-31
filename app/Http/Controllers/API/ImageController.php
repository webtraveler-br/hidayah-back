<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Image;

class ImageController extends Controller
{
    public function store(Request $request){

      // função de validação da rota store
      $validator = Validator::make($request->all(), [
        'imagem' => ['required', 'image', 'max:10000'],
        'descricao' => ['required', 'string']
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors(),
          'code' => '400'
        ], 400);
      }

      // armazena imagem e ja adiciona o caminho pros dados
      $path = Storage::putFile('public/images', $request->file('imagem'), 'public');
      Image::create([
        'caminho' => 'storage'.substr($path, 6),
        'descricao' => $request->descricao
      ]);

      return response()->json([
        'message' => ['Imagem salva com sucesso'],
        'code' => '201'
      ], 201);
    }

    public function index(){
      $image = Image::all();

      return response()->json([
        'message' => $image,
        'code' => '200'
      ], 200);
    }

    public function show($id){
      $image = Image::find($id);
      if (!$image) {
        return response()->json([
          'error' => ['Imagem não encontrada'],
          'code' => '404'
        ], 404);
      }

      return response()->json([
        'message' => $image,
        'code' => '200'
      ], 200);
    }

    public function destroy($id){

      $image = Image::find($id);
      if (!$image) {
        return response()->json([
          'error' => ['Imagem não encontrada'],
          'code' => '404'
        ], 404);
      }

      if (sizeof($image->comments) or sizeof($image->portfolios)) {
        return response()->json([
          'error' => ['Essa imagem tem relações'],
          'code' => '409'
        ], 409);
      }

      Storage::delete('public'.substr($image->path, 7));
      $image->delete();
      return response()->json([
        'message' => ['Imagem deletada com sucesso'],
        'code' => '200'
      ], 200);
    }

    public function update(Request $request, $id){

      $validator = Validator::make($request->all(), [
        'imagem' => ['nullable', 'image', 'max:10000'],
        'descricao' => ['nullable', 'string']
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors(),
          'code' => '400'
        ], 400);
      }

      $image = Image::find($id);
      if (!$image) {
        return response()->json([
          'error' => ['Imagem não encontrada'],
          'code' => '404'
        ], 404);
      }

      // faz alteração de somente um, ou quantos houverem (nesse caso 2)
      if (isset($request->imagem)) {
        Storage::delete($image->caminho);
        $image->caminho = Storage::putFile('public/images', $request->file('imagem'), 'public');
      } elseif (isset($request->descricao)) {
        $image->descricao = $request->descricao;
      }
      $image->save();
      return response()->json([
        'message' => ['Imagem atualizada com sucesso'],
        'code' => '200'
      ], 200);

    }
}
