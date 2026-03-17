<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Produto::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $produto = new Produto;
        
        $request->validate([
            'nome' => ['required', 'max:50'],
            'descricao' => ['required', 'max:100'],
            'preco' => ['required', 'gte:0'],
            'validade' => ['required', 'date', Rule::date()->afterToday()],
            'imagem' => ['required', 'unique:produtos'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ]);

        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;
        $produto->validade = $request->validade;
        $produto->imagem = $request->imagem;
        $produto->categoria_id = $request->categoria_id;
 
        $produto->save();
 
        return response()->json($produto, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        return $produto;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => ['required', 'max:50'],
            'descricao' => ['required', 'max:100'],
            'preco' => ['required', 'gte:0'],
            'validade' => ['required', 'date', Rule::date()->afterToday()],
            'imagem' => ['required', 'unique:produtos'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ]);

        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;
        $produto->validade = $request->validade;
        $produto->imagem = $request->imagem;
        $produto->categoria_id = $request->categoria_id;
 
        $produto->save();
 
        return response()->json($produto, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        $produto -> delete();
    }
}
