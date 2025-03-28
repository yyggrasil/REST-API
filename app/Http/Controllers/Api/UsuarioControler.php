<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\User;

class UsuarioControler extends Controller
{
    public function index()
    {
        return response()->json([
            'message'=>'Meu primeiro acesso ao controlador',
            'status'=>200
        ],200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|email|string|max:255|unique:users,email',
            'password'=>'sometimes|required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Erro nas indormações do usuário',
                'status'=>404,
                'errors'=>$validator->errors()
            ],404);
        }

        $data = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        return response()->json([
            'message'=>'Usuário cadastrado com sucesso',
            'status'=>200,
            'data'=>$data
        ],200);
    }

    public function show(Request $request, string $id)
    {
        try{
            $data = User::findOrfail($id);
        } catch (HttpResponseException $e) {
            response()->json([
                'message'=>$e->getMessage(),
                'status'=>404
            ],404);
        }
        
        return response()->json([
            'message'=>'Usuário encontrado com sucesso',
            'status'=>200,
            'data'=>$data
        ],200);
    }

    public function update(Request $request, $id)
    {
        
    }

    public function destroy(Request $request, string $id)
    {
        $data = User::find($id);

        if (!$data) {
            return response()->json([
                'message'=>'Usuário não encontrado',
                'status'=>404
            ],404);
        }

        $data->delete();

        return response()->json([
            'message'=>'Usuário deletado com sucesso',
            'status'=>200
        ],200);
    }
}
