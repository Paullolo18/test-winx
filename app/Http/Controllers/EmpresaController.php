<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = auth()->user()->empresa;
        return response()->json($empresas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:empresas,email',
            'cnpj' => 'required|unique:empresas,cnpj',
        ]);

        $empresa = new Empresa($request->all());
        $empresa->user_id = auth()->id();
        $empresa->save();

        return response()->json($empresa, 201);
    }

    public function show()
    {
        $empresa = Empresa::where('user_id', Auth::id())->first();

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        return response()->json($empresa);
    }


    public function update(Request $request, $id)
    {
        $empresa = auth()->user()->empresas()->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:empresas,email,' . $empresa->id,
            'cnpj' => 'required|unique:empresas,cnpj,' . $empresa->id,
        ]);

        $empresa->update($request->all());

        return response()->json($empresa);
    }

    public function destroy($id)
    {
        $empresa = auth()->user()->empresas()->findOrFail($id);

        $empresa->delete();

        return response()->json(['message' => 'Empresa excluída com sucesso.']);
    }
}
