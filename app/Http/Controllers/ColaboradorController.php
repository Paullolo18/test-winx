<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Http\Resources\ColaboradorResource;


class ColaboradorController extends Controller
{
    public function index(Request $request)
    {
        $colaboradores = Colaborador::query()
            ->when($request->nome, fn($query) => $query->where('nome', 'like', "%{$request->nome}%"))
            ->when($request->cargo, fn($query) => $query->where('cargo', 'like', "%{$request->cargo}%"))
            ->when($request->data_admissao, fn($query) => $query->whereDate('data_admissao', $request->data_admissao))
            ->with('empresa') // Relacionamento com a empresa
            ->get();

        return response()->json([
            'data' => $colaboradores, // DataTables precisa dessa chave 'data'
        ]);
    }

    public function show($id)
    {
        $colaborador = Colaborador::findOrFail($id);

        return new ColaboradorResource($colaborador);
    }
}
