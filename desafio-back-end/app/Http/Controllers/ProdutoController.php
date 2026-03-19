<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produtos = Produto::paginate(15);
        foreach($produtos as $produto){
            $produto->url = Storage::url($produto->imagem);
            $produto->categoria = Categoria::find($produto->categoria_id)->nome;
        } 
        return $produtos;
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
            'imagem' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ]);

        $file = $request->file('imagem');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('images', 'public');
        

        $produto->nome = $request->nome;
        $produto->descricao = $request->descricao;
        $produto->preco = $request->preco;
        $produto->validade = $request->validade;
        $produto->imagem = $path;
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
            'imagem' => ['required', Rule::unique('produtos')->ignore($produto->id)],
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
        return response()->json($produto, 200);
    }

    public function search(String $prompt)
    {
        return Produto::where('nome','like','%'.$prompt.'%')->orWhere('descricao','like','%'.$prompt.'%')->paginate(15);
    }

    public function categories()
    {
        return Categoria::all();
    }
}
