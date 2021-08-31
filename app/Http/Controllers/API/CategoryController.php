<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Category;
use App\Portfolio;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
          'nome' => ['required', 'string'],
          'portifolios' => ['nullable', 'array'],
          'portifolios.*' => ['required', 'integer']
        ]);
        if ($validator->fails()) {
          return response()->json([
            'error' => $validator->errors(),
            'code' => '400'
          ], 400);
        }

        if (isset($request->portifolios)) {
          // retira valores repetidos para não salvar em categorias iguais
          $request->portifolios = array_unique($request->portifolios);
          foreach ($request->portifolios as $portfolio) {
            if (!Portfolio::find($portfolio)) {
              return response()->json([
                'error' => ["Portifólio $portfolio não encontrado"],
                'code' => '404'
              ], 404);
            }
          }
        }
        $category = new Category;
        $category->nome = $request->nome;
        $category->save();
        if (isset($request->portifolios)) {
          $category->portifolios()->attach($request->portifolios);
        }
        return response()->json([
          'message' => ['Categoria salva com sucesso'],
          'code' => '201'
        ], 201);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $categories = Category::with('portifolios')->get();
        return response()->json([
          'message' => $categories,
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
        $category = Category::with('portifolios')->find($id);
        if (!$category) {
          return response()->json([
            'error' => ['Categoria não encontrada'],
            'code' => '404'
          ], 404);
        }

        return response()->json([
          'message' => $category,
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
      $category = Category::find($id);
      if (!$category) {
        return response()->json([
          'error' => ['Categoria não encontrada'],
          'code' => '404'
        ], 404);
      }

      $validator = Validator::make($request->all(), [
        'nome' => ['nullable', 'string'],
        'portifolios' => ['nullable', 'array'],
        'portifolios.*' => ['nullable', 'integer']
      ]);
      if ($validator->fails()) {
        return response()->json([
          'error' => $validator->errors(),
          'code' => '400'
        ], 400);
      }

      // esse código verifica o primeiro index do array devido
      // a inabilidade do postman de enviar um array vazio
      // enviando somente com a primeira posição como null
      if (isset($request->portifolios[0])) {
        // retira valores repetidos para não salvar em categorias iguais
        $request->portifolios = array_unique($request->portifolios);
        // verifica se os portifolios existem
        foreach ($request->portifolios as $portifolio) {
          $portfolio = Portfolio::find($portifolio);
          if (!$portfolio) {
            return response()->json([
              'error' => ["Portifólio $portifolio não encontrado"],
              'code' => '404'
            ], 404);
          }
        }
        // pega os portifolios que serão eliminados pelo sync
        // e verifica se eles ficarão sem categoria
        // considerando os portifolios inseridos
        foreach ($category->portifolios as $portifolio) {
          if (
            !in_array($portifolio->id, $request->portifolios) and
            sizeof($portifolio->categorias) === 1
          ) {
              return response()->json([
                'error' => ["Portifólio $portifolio->id tem somente esta categoria"],
                'code' => '409'
              ], 409);
          }
        }
      } elseif (isset($request->portifolios)) {
        // verifica se algum portifolio só tem essa categoria
        // considerando que o update irá retirar todos
        foreach ($category->portifolios as $portifolio) {
          if (sizeof($portifolio->categorias) === 1) {
              return response()->json([
                'error' => ["Portifólio $portifolio->id tem somente esta categoria"],
                'code' => '409'
              ], 409);
          }
        }
      }
      $category->nome = $request->nome ? $request->nome : $category->nome;
      $category->save();
      if (isset($request->portifolios[0])) {
        $category->portifolios()->sync($request->portifolios);
      } else {
        $category->portifolios()->sync(null);
      }
      return response()->json([
        'message' => ['Categoria salva com sucesso'],
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
      $category = Category::find($id);
      if (!$category) {
        return response()->json([
          'error' => ['Categoria não encontrada'],
          'code' => '404'
        ], 404);
      }

      if (sizeof($category->portifolios)) {
        return response()->json([
          'error' => ['Alguns portifolios pertencem a esta categoria'],
          'code' => '409'
        ], 409);
      }

      $category->delete();
      return response()->json([
        'message' => ['Categoria deletada com sucesso'],
        'code' => '200'
      ], 200);
    }
}
