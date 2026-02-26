<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Acesso;
use App\Models\Afiliado;
use App\Models\Usuario;
use App\Http\Resources\AcessoResource;
// importar validator
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RedefinirSenhaMail;

class AcessoController extends Controller
{
    /**
     * Gerar código para redefinição de senha
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gerarCodigoRedefinicao(Request $request) {
        
        // 1. VALIDAÇÃO PRIMEIRO
        // Isso retorna um erro 422 (Unprocessable Entity) automaticamente,
        // que é o correto para erros de validação.
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ],
        [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // 2. BUSCA O USUÁRIO
        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            // Retorna 404 (Not Found), que é o correto para "não encontrado".
            return response()->json(['message' => 'E-mail não encontrado!'], 404);
        }

        try {
            // 3. GERA UM CÓDIGO DE 6 DÍGITOS
            $codigo = rand(100000, 999999);

            // 4. USA A TABELA 'password_resets' PADRÃO DO LARAVEL
            // Isso é mais seguro, pois inclui um timestamp de expiração.
            DB::beginTransaction();
            
            // Remove códigos antigos deste e-mail
            DB::table('password_resets')->where('email', $request->email)->delete();
            
            // Salva o novo código (sem hash, pois o usuário precisa digitá-lo)
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $codigo, // Salva o código de 6 dígitos
                'created_at' => now()
            ]);

            // 5. ENVIA O E-MAIL USANDO A CLASSE MAILABLE
            // O Mailable usará as credenciais do .env automaticamente
            Mail::to($usuario)->send(new RedefinirSenhaMail($codigo, $usuario->nome));

            DB::commit();

            return response()->json(['message' => 'Código enviado para o e-mail!'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // Loga o erro real para você poder ver no servidor
            \Log::error('Erro ao enviar e-mail de redefinição: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao enviar o e-mail. Tente novamente mais tarde.',
        'erro' => $e->getMessage()], 500);
        }
    }

    public function verificarCodigoRedefinicao(Request $request) {
        $dadosValidados = Validator::make($request->all(), [
            'email' => 'required|email',
            'codigo' => 'required',
            'password' => 'required|confirmed'
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
            'codigo.required' => 'O campo código é obrigatório.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.confirmed' => 'A confirmação da senha não corresponde.'
        ]);
        if (!$request->codigo || !$request->password || !$request->password_confirmation || !$request->email) {
            return response()->json(['message' => 'Informe todos os campos!'], 500);
        }

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->codigo)
            ->first();

        if ($record) {
            // verifica se o token tem mais de 24 horas
            $createdAt = \Carbon\Carbon::parse($record->created_at);
            if ($createdAt->diffInHours(now()) > 24) {
                return response()->json(['message' => 'Código expirado!'], 500);
            }

            // faz a alteração da senha
            $usuario = Usuario::where('email', $request->email)->first();
            if ($usuario) {
                $usuario->password = bcrypt($request->password);
                $usuario->update();
                // remove o token usado
                DB::table('password_resets')->where('email', $request->email)->delete();
                return response()->json(['message' => 'Senha redefinida com sucesso!'], 200);
            } else {
                return response()->json(['message' => 'Usuário não encontrado!'], 500);
            }
        } else {
            return response()->json(['message' => 'Código inválido!'], 500);
        }
    }

    public function redefinirSenha (Request $request) {
        if (!$request->token || !$request->email || !$request->password || !$request->password_confirmation || ($request->password !== $request->password_confirmation)) {
            return response()->json(['message' => 'Token Inválido!'], 500);
        }

        $afiliado = Afiliado::where('email', $request->email)->where('token', $request->token)->first();
        $usuario = Usuario::where('email', $request->email)->where('token', $request->token)->first();
        if ($afiliado || $usuario) {
            if ($afiliado) {
                $afiliado->password = bcrypt($request->password);
                $afiliado->token = null;
                $afiliado->update();
            } else {
                $usuario->password = bcrypt($request->password);
                $usuario->token = null;
                $usuario->update();
            }

            return response()->json(['message' => 'Redefinido Com Sucesso!'], 200);
        } else {
            return response()->json(['message' => 'Token Inválido!'], 500);
        }
    }

    public function verificarAcesso(Request $request)
    {
        if (\Auth::user()->acesso_id) {
            $acesso = Acesso::find(\Auth::user()->acesso_id);
            if ($acesso[$request->tela] == 'S') {
                return response()->json(['acesso' => true], 200);
            }
        }
        return response()->json(['acesso' => false], 200);
    }

    public function listar()
    {
        return Acesso::select('id', 'nome')->get();
    }

    public function buscar(Request $request)
    {
        if ($request->id) {
            return AcessoResource::make(Acesso::with('usuariosRelacionados')->find($request->id));
        }

        $busca = Acesso::with('usuariosRelacionados')->select('*');

        if ($request->pagination) {
            return AcessoResource::collection($busca->orderBy('titulo')->paginate($request->pagination));
        }
        return AcessoResource::collection($busca->orderBy('titulo')->get());

    }

    public function salvar(Request $request)
    {
        \DB::beginTransaction();
        try {
            $model = new Acesso();
            $model->fill($request->all());
            $model->save();

            foreach ($request->usuariosRelacionados as $usuario) {
                $usuario = Usuario::find($usuario['id']);
                $usuario->acesso_id = $model->id;
                $usuario->update();
            }

            \DB::commit();
            return $model;
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'code'      =>  500,
                'message'   =>  'Erro ao salvar registro.'
            ], 500);
        }
    }

    public function atualizar(Request $request)
    {
        \DB::beginTransaction();
        try {

            $model = Acesso::find($request->id);
            $model->fill($request->all());
            $model->update();

            Usuario::where('acesso_id', $model->id)->update(['acesso_id' => null]);
            foreach ($request->usuariosRelacionados as $usuario) {
                $usuario = Usuario::find($usuario['id']);
                $usuario->acesso_id = $model->id;
                $usuario->update();
            }

            \DB::commit();
            return $model;
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'code'      =>  500,
                'message'   =>  'Erro ao atualizar registro.'
            ], 500);
        }
    }
    
    public function remover ($id) {
        \DB::beginTransaction();
        try {
            
            Usuario::where('acesso_id', $id)->update(['acesso_id' => null]);
            Acesso::destroy($id);

            \DB::commit();
            return 'true';
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'code'      =>  500,
                'message'   =>  'Erro ao remover registro.'
            ], 500);
        }
    }
    
    public function perfil (Request $request)
    {
        return \Auth::user();
    }
}
