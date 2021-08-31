<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Portfolio;
use App\Image;
use App\Category;

class PortfolioController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      $validator = Validator::make($request->all(), [
        'titulo' => ['required', 'string'],
        'link' => ['required', 'URL'],
        'imagem_id' => ['required', 'integer'],
        'categorias' => ['required', 'array'],
        'categorias.*' => ['required', 'integer']
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

      foreach ($request->categorias as $category) {
        $request->categorias = array_unique($request->categorias);
        if (!Category::find($category)) {
          return response()->json([
            'error' => ["Categoria $category não encontrada"],
            'code' => '404'
          ], 404);
        }
      }

      // adiciona os valores em suas colunas
      // apesar de nesse caso a quantidade de linhas permanecer a mesma
      // em casos com mais colunas ele salvaria algumas
      $portfolio = new Portfolio;
      foreach (['titulo', 'link', 'imagem_id'] as $column) {
        $portfolio->$column = $request->$column;
      }
      $portfolio->save();
      // retira valores repetidos para não salvar em categorias iguais
      $portfolio->categorias()->attach($request->categorias);
      return response()->json([
        'message' => ['Portifólio criado com sucesso'],
        'code' => '201'
      ], 201);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
      // o with está aqui para que seja possivel usa-lo no index de categorias tambem
      // tornando mais facil a organização da seção portfolio
        $portfolios = Portfolio::with('categorias')->get();
        return response()->json([
          'message' => $portfolios,
          'code' => '200'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
      // o with está aqui para que seja possivel usa-lo no index de categorias tambem
      // tornando mais facil a organização da seção portfolio
        $portfolio = Portfolio::with('categorias')->find($id);
        if (!$portfolio) {
          return response()->json([
            'error' => ['Portifólio não encontrado'],
            'code' => '404'
          ], 404);
        }

        return response()->json([
          'message' => $portfolio,
          'code' => '200'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
      $portfolio = Portfolio::find($id);
      if (!$portfolio) {
        return response()->json([
          'error' => ['Portifólio não encontrado'],
          'code' => '404'
        ], 404);
      }

      $validator = Validator::make($request->all(), [
        'titulo' => ['nullable', 'string'],
        'link' => ['nullable', 'URL'],
        'imagem_id' => ['nullable', 'integer'],
        'categorias' => ['nullable', 'array'],
        'categorias.*' => ['required', 'integer']
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors(),
          'code' => '400'
        ], 400);
      }

      if (isset($request->imagem_id)) {
        $image = Image::find($request->imagem_id);
        if (!$image) {
          return response()->json([
            'error' => ['Imagem não encontrada'],
            'code' => '404'
          ], 404);
        }
      }

      // se houver categorias ele substitui no banco de dados
      // verificação para que o portifólio nunca fique sem categoria
      if (isset($request->categorias)) {
        // só adiciona categorias não repetidas
        $request->categorias = array_unique($request->categorias);
        foreach ($request->categorias as $category) {
          if (!Category::find($category)) {
            return response()->json([
              'error' => ["Categoria $category não encontrada"],
              'code' => '404'
            ], 404);
          }
        }
      }

      // se não for null, salva o valor da request para as colunas passadas
      foreach (['titulo', 'link', 'imagem_id'] as $column) {
        $portfolio->$column = $request->$column ? $request->$column : $portfolio->$column;
      }
      $portfolio->save();
      if (isset($request->categorias)) {
        $portfolio->categorias()->sync($request->categorias);
      }
      return response()->json([
        'message' => ['Portifólio salvo com sucesso'],
        'code' => '200'
      ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
      $portfolio = Portfolio::find($id);
      if (!$portfolio) {
        return response()->json([
          'error' => ['Portifólio não encontrado'],
          'code' => '404'
        ], 404);
      }

      $portfolio->categorias()->sync(null);
      $portfolio->delete();
      return response()->json([
        'message' => ['Portifólio deletado com sucesso'],
        'code' => '200'
      ], 200);
    }
}
