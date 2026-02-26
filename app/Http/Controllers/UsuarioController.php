<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Usuario;
use App\Models\Empresa;
use App\Http\Resources\UsuarioResource;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

use App\Mail\NotificacaoCadastro;
use App\Mail\ConfirmacaoCadastro;

use Illuminate\Support\Facades\Auth;

use App\Rules\Cpf;
use App\Rules\Cnpj;
use App\Rules\Telefone;

class UsuarioController extends Controller
{
    
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $usuario = Usuario::whereRaw("upper(email) = upper(?)", [$request->email])->first();
            Auth::login($usuario);
            $user = Auth::user();
                return response()->json(['redirect' => '/dashboard/admin']);
        }

        return response()->json([
            'code'      =>  500,
            'message'   =>  'Falha na Autenticação.'
        ], 500);
    }

    public function resetSenha (Request $request) {

        if (!$request->email) {
            return response()->json(['message' => 'Informe um e-mail válido.'], 500);
        }

        if ($usuario = Usuario::where('email', $request->email)->first()) {
            $token = Str::random(255);
            $usuario->token = $token;
            $usuario->update();
            $link = env('APP_URL').'/redefinir?token='.$token;

            $data = [
                'name' => $usuario->nome,
                'resetLink' => $link
            ];

            $emailUsuario = $usuario->email;
    
            Mail::send('layout.email_redefinir_senha', $data, function ($message) use ($emailUsuario) {
                $message->to($emailUsuario)->subject('Redefinição de Senha');
            });
        }

        return response()->json(['message' => 'Verifique o e-mail informado para redefinir sua senha.'], 200);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'code'      =>  200
        ], 200);
    }

    public function verificarEmail(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('home')->withErrors(['message' => 'Link de verificação inválido ou expirado.']);
        }

        $usuario = Usuario::find($id);
        if (!$usuario) {
            return redirect()->route('home')->withErrors(['message' => 'Usuário não encontrado.']);
        }
        if ($usuario->status === 'ativo') {
            return redirect()->route('home')->with('message', 'Email já verificado.');
        }

        try {
            \DB::beginTransaction();
            $usuario->status = 'ativo';
            $usuario->save();
            
            $empresa = Empresa::where('id', $usuario->empresa_id)->first();
            $empresa->renovacao_plano = now()->addDays(15);
            $empresa->save();
            \DB::commit();
            return redirect()->route('home')->with('message', 'Email verificado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do usuário: ' . $e->getMessage());
            return redirect()->route('home')->withErrors(['message' => 'Ocorreu um erro ao verificar o email. Tente novamente mais tarde.']);
        }
    }

    public function menu()
    {
        $menuGerenciarLoja = [
            ['titulo' => 'Categoria de produtos', 'link' => '/categorias', 'icone' => 'mdi-shape-outline'],
            ['titulo' => 'Produtos', 'link' => '/produtos', 'icone' => 'mdi-tag-multiple'],
            ['titulo' => 'Formas de Pagamento', 'link' => '/formas-pagamento', 'icone' => 'mdi-credit-card-outline'],
            ['titulo' => 'Categoria de adicionais', 'link' => '/categorias-adicionais', 'icone' => 'mdi-shape-outline'],
            ['titulo' => 'Adicionais', 'link' => '/adicionais', 'icone' => 'mdi-plus-circle-outline'],
            ['titulo' => 'Limite de Adicionais', 'link' => '/limiteadicionais', 'icone' => 'mdi-filter-outline'],
            ['titulo' => 'Grupo de tamanhos', 'link' => '/grupotamanhos', 'icone' => 'mdi-folder-outline'],
            ['titulo' => 'Tamanhos', 'link' => '/tamanhos', 'icone' => 'mdi-ruler-square-compass'],
            ['titulo' => 'Cupons de Desconto', 'link' => '/cuponsdesconto', 'icone' => 'mdi-ticket-percent-outline'],
            ['titulo' => 'Promoções', 'link' => '/promocoes', 'icone' => 'mdi-tag-heart-outline'],
            ['titulo' => 'Programa fidelidade', 'link' => '/programafidelidade', 'icone' => 'mdi-star-outline'],
            ['titulo' => 'Taxa de entrega', 'link' => '/taxadeentrega', 'icone' => 'mdi-truck-outline'],
            ['titulo' => 'Histórico de pagamentos', 'link' => '/pagamentos', 'icone' => 'mdi-cash'],
        ];
        $menuParceiro = [
            ['titulo' => 'Dashboard', 'link' => '/dashboard/default', 'icone' => 'mdi-view-dashboard'],
            ['titulo' => 'Pedidos', 'link' => '/pedidos', 'icone' => 'mdi-cart'],
            ['titulo' => 'Produtos', 'link' => '/produtos', 'icone' => 'mdi-tag-multiple'],
            ['titulo' => 'Loja', 'icone' => 'mdi-storefront', 'items' => $menuGerenciarLoja],
            ['titulo' => 'Configurações', 'link' => '/configuracoes', 'icone' => 'mdi-cog-outline'],
            ['titulo' => 'Indique e ganhe', 'link' => '/indique', 'icone' => 'mdi-account-multiple-plus'],
            ['titulo' => 'Solicitar Suporte', 'link' => '/suportetecnico', 'icone' => 'mdi-help-circle-outline'],
        ];
        $usuario = Auth::user();
        if ($usuario->acesso == 'parceiro') {
            return response()->json($menuParceiro);
        } elseif ($usuario->acesso == 'admin') {
            $menuAdmin = [
                ['titulo' => 'Dashboard Admin', 'link' => '/dashboard/admin', 'icone' => 'mdi-view-dashboard'],
                ['titulo' => 'Usuários', 'link' => '/usuarios', 'icone' => 'mdi-account-multiple'],
                ['titulo' => 'Campanhas', 'link' => '/campanhas', 'icone' => 'mdi-bullhorn'],
                ['titulo' => 'Aprovações pendentes', 'link' => '/analisedocumentos', 'icone' => 'mdi-file-document'],
                ['titulo' => 'Suporte', 'link' => '/suporte', 'icone' => 'mdi-help-circle-outline']
            ];
            return response()->json($menuAdmin);
        }
        
    }

    public function buscarAniversariantes (Request $request) {
        $meses = Usuario::select(\DB::raw("month(nascimento) as mes_nascimento"))
            ->whereNotNull('nascimento')
            ->groupBy('mes_nascimento')
            ->orderBy('mes_nascimento')
            ->get();
        $aniversariantes = [];
        foreach ($meses as $mes) {
            array_push($aniversariantes, [
                'mes' => $mes['mes_nascimento'],
                'colaboradores' => Usuario::whereRaw("month(nascimento) = ?", [$mes['mes_nascimento']])->orderBy('nascimento')->get()
            ]);
        }

        return response()->json($aniversariantes);
    }

    public function buscarUsuarioLogado()
    {
        $usuario = Auth::user();
        if ($usuario) {
            return UsuarioResource::make($usuario);
        }
        return response()->json(['message' => 'Usuário não autenticado'], 401);
    }

    public function buscar(Request $request)
    {
        if ($request->id) {
            $usuario = Usuario::with('indicacaoMaisRecente')->find($request->id);
            if ($usuario) {
                // A lógica para um único usuário permanece a mesma
                $usuario->status_indicacao = $usuario->indicacaoMaisRecente->status ?? null;
            }
            return UsuarioResource::make($usuario);
        }

        $busca = Usuario::with('indicacaoMaisRecente');

        if ($filtro = $request->nome) {
            $busca->where('nome', 'like', '%'.$filtro.'%');
        }
        if ($filtro = $request->email) {
            $busca->where('email', 'like', '%'.$filtro.'%');
        }

        // --- LÓGICA DO FILTRO DE STATUS (ALTERADO) ---
        if ($statusFiltro = $request->status) {
            if ($statusFiltro === 'sem_indicacao') {
                $busca->whereDoesntHave('indicacaoMaisRecente');
            } else {
                $busca->whereHas('indicacaoMaisRecente', function($query) use ($statusFiltro) {
                    $query->where('status', $statusFiltro);
                });
            }
        }

        $busca->orderBy('nome');

        $usuarios = $request->pagination ? $busca->paginate($request->pagination) : $busca->get();
        
        // A transformação para adicionar o status continua válida e segura
        $usuarios->through(function ($usuario) {
            $usuario->status_indicacao = $usuario->indicacaoMaisRecente->status ?? null;
            return $usuario;
        });

        return UsuarioResource::collection($usuarios);
    }

    public function atualizarAcesso(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|integer|exists:usuarios,id',
            'acesso_id'  => 'required|integer|exists:acesso,id',
        ]);

        $usuario = \App\Models\Usuario::findOrFail($request->usuario_id);
        
        if ($usuario->id == 1) {
            return response()->json(['message' => 'Não é permitido alterar o grupo de acesso do administrador principal.'], 403);
        }

        $usuario->acesso_id = $request->acesso_id;
        $usuario->save();

        return response()->json(['message' => 'Nível de acesso atualizado com sucesso.']);
    }

    public function salvar(Request $request)
    {
        // 1. Criar a instância do Validador
        $validator = \Validator::make($request->all(), [
            'nome' => ['required', 'string', 'max:60'],
            'cpf' => ['required', 'string', new Cpf, 'unique:usuarios,cpf'],
            'telefone' => ['required', 'string', new Telefone],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nome_empresa' => ['required', 'string', 'max:100'],
            'slogan' => ['nullable', 'string', 'max:200'],
            'cnpj' => ['nullable', 'string', 'unique:empresas,cnpj', new Cnpj],
            'telefone_empresa' => ['nullable', 'string', new Telefone],
            'cep' => ['required', 'string', 'max:9'],
            'rua' => ['required', 'string', 'max:100'],
            'numero' => ['required', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:50'],
            'bairro' => ['required', 'string', 'max:50'],
            'estado' => ['required', 'string', 'max:2'],
            'cidade' => ['required', 'string', 'max:50'],
        ], [
            'required' => 'O campo :attribute é obrigatório.', 
            'digits_between' => 'O campo :attribute deve ter entre :min e :max dígitos.',
            'max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'digits' => 'O campo :attribute deve ter :digits dígitos.',
            'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
            'unique' => 'O campo :attribute já está em uso.',
            'password.confirmed' => 'A confirmação de senha não confere.',
        ]);

        // 2. VERIFICAR SE HOUVE ERRO
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors() // Retorna a lista de erros para o front
            ], 422); // 422 é o padrão para erro de validação (Unprocessable Entity)
        }

        // 3. Pegar os dados já validados e limpos
        // O validated() retorna um ARRAY, não um objeto.
        $dados = $validator->validated();
        $slugEmpresa = Str::slug($dados['nome_empresa'], '-');

        //Verifica se o slug já existe
        $slugExistente = Empresa::where('slug_empresa', $slugEmpresa)->first();
        if ($slugExistente) {
            $slugEmpresa .= '-' . $dados['cidade'];
        }

        try {
            \DB::beginTransaction();
            $empresa = new Empresa();
            $empresa->fill([
                'nome_fantasia' => $dados['nome_empresa'], // Note que mapeamos nome_empresa -> nome_fantasia
                'slogan' => $dados['slogan'] ?? null,
                'slug_empresa' => $slugEmpresa,
                'cnpj' => isset($dados['cnpj']) ? preg_replace('/[^0-9]/', '', $dados['cnpj']) : null,
                'telefone_empresa' => $dados['telefone_empresa'] ?? null,
                'cep' => $dados['cep'],
                'rua' => $dados['rua'],
                'numero' => $dados['numero'],
                'complemento' => $dados['complemento'] ?? null,
                'bairro' => $dados['bairro'],
                'cidade' => $dados['cidade'],
                'estado' => $dados['estado'],
            ]);

            $empresa->save();

            $usuario = new Usuario();
            $usuario->fill([
                'empresa_id' => $empresa->id,
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
                'cpf' => preg_replace('/[^0-9]/', '', $dados['cpf']), 
                'password' => bcrypt($dados['password']),
                'acesso' => 'parceiro',
                'status' => 'pendente_confirmacao',
            ]);
            $usuario->save();

            try {
                // Envia para o próprio usuário que acabou de se cadastrar
                Mail::to($usuario->email)->send(new ConfirmacaoCadastro($usuario, $empresa));
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de confirmação: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Erro ao enviar email de confirmação. Tente novamente ou contate o suporte.',
                ], 500);
            }

            \DB::commit();
            return response()->json([
                'message' => 'Cadastro realizado com sucesso. Verifique seu email!',
                'data'    => $usuario
            ], 201);


        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Erro ao cadastrar usuário: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocorreu um erro interno ao salvar o registro.',
            ], 500);
        }
    }

    public function atualizar(Request $request)
    {
        \DB::beginTransaction();
        try {
            $regras = [
                'nome'            => ['required', 'string', 'max:255'],
                'email'           => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($request->id)],
                'cpf'             => ['required', 'string', new Cpf],
                'rg'              => ['required', 'string', 'numeric'],
                'password'        => ['string', 'min:8', 'confirmed'],
            ];
            $mensagensDeErro = [
                'required' => 'O campo :attribute é obrigatório.', 
                'nome.max' => 'O nome não pode ter mais de :max caracteres.',
                'cpf.required' => 'Por favor, informe o CPF.',
                'cpf.unique' => 'Este CPF já está cadastrado.',
                'email.unique' => 'Este e-mail já está em uso.',
                'email.email' => 'O e-mail informado não é válido.',
                'email.max' => 'O e-mail não pode ter mais de :max caracteres.',
                'password.confirmed' => 'A confirmação de senha não confere.',
                'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            ];
            $validator = Validator::make($request->all(), $regras, $mensagensDeErro);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Os dados fornecidos são inválidos.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            if ($request->password && $request->password_confirmation && $request->password !== $request->password_confirmation) {
                return response()->json([
                    'code'      =>  500,
                    'message'   =>  'Senhas Diferentes.'
                ], 500);
            }

            $model = Usuario::find($request->id);
            $model->fill($request->all());
            if ($request->password && $request->password_confirmation && $request->password === $request->password_confirmation) {
                $model->password = bcrypt($request->password);
            }
            $model->update();

            \DB::commit();
            return $model;
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'code'      =>  500,
                'e' => $e->getMessage(),
                'message'   =>  'Erro ao atualizar registro.'
            ], 500);
        }
    }
    
    public function remover ($id) {
        \DB::beginTransaction();
        try {
            if($id == Auth::user()->id) {
                return response()->json([
                    'code'      =>  500,
                    'message'   =>  'Não é possível remover o usuário logado.'
                ], 500);
            }
            
            Usuario::destroy($id);

            \DB::commit();
            return 'true';
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'code'      =>  500,
                'message'   =>  'Erro ao remover registro.'. $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aprova o cadastro de um usuário.
     */
    public function aprovar(Usuario $usuario)
    {
        if (Auth::user()->acesso->id != 2) {
            return response()->json(['message' => 'Ação não autorizada.'. Auth::user()->acesso->id], 403);
        }

        \DB::beginTransaction();
        try {
            $usuario->status = 'aprovado';
            $usuario->save();

            $usuario->rejeicoes()->delete();

            // Criar informe para o usuário indicador (se existir)
            $this->criarInformeUsuarioAprovado($usuario);

            \DB::commit();
            return response()->json(['message' => 'Usuário aprovado com sucesso!']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Erro ao aprovar usuário: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Rejeita o cadastro de um usuário e armazena o motivo.
     */
    public function rejeitar(Request $request, Usuario $usuario)
    {
        \DB::beginTransaction();
        try {
            if (Auth::user()->acesso->id != 2) {
                return response()->json(['message' => 'Ação não autorizada.' . Auth::user()->acesso->id], 403);
            }

            // Validação do motivo de rejeição
            $request->validate([
                'motivo' => 'required|string|max:1000',
            ]);

            $usuario->status = 'rejeitado';
            $usuario->save();

            // Cria o registro na nova tabela 'rejeicoes'
            $usuario->rejeicoes()->create([
                'motivo' => $request->motivo,
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Erro ao rejeitar usuário: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Usuário rejeitado com sucesso.']);
    }

    /**
     * Cria informe para o usuário indicador quando um novo usuário é aprovado
     */
    private function criarInformeUsuarioAprovado($usuarioAprovado)
    {
        try {
            // Buscar a indicação para este usuário
            $indicacao = Indicacao::where('indicado_id', $usuarioAprovado->id)->first();
            
            if ($indicacao && $indicacao->indicador_id) {
                // Verificar se o indicador ainda existe e não é administrador
                $indicador = Usuario::where('id', $indicacao->indicador_id)
                                   ->where('acesso_id', '!=', 2)
                                   ->first();
                
                if ($indicador) {
                    Informes::criar([
                        'titulo' => 'Indicação Aprovada',
                        'descricao' => "O usuário '{$usuarioAprovado->nome}' que você indicou foi aprovado e agora faz parte da nossa rede!",
                        'tipo' => Informes::TIPO_NOVO_CADASTRO,
                        'usuario_id' => $indicador->id,
                        'referencia_id' => $usuarioAprovado->id,
                        'referencia_tipo' => get_class($usuarioAprovado),
                        'data_evento' => now(),
                        'dados_adicionais' => [
                            'usuario_aprovado_nome' => $usuarioAprovado->nome,
                            'usuario_aprovado_email' => $usuarioAprovado->email,
                            'campanha_id' => $indicacao->campanha_id ?? null
                        ]
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log do erro mas não impede a aprovação do usuário
            \Log::error('Erro ao criar informe de usuário aprovado: ' . $e->getMessage());
        }
    }

    /**
     * Verifica se existem treinamentos disponíveis e cria informe para o novo usuário
     */
    private function verificarTreinamentosDisponiveis($usuario)
    {
        try {
            // Verificar se existem treinamentos cadastrados no sistema
            $totalTreinamentos = Treinamentos::count();
            
            if ($totalTreinamentos > 0) {
                // Criar informe notificando sobre treinamentos disponíveis
                Informes::criar([
                    'titulo' => 'Treinamentos Disponíveis',
                    'descricao' => "Bem-vindo(a) {$usuario->nome}! Temos {$totalTreinamentos} treinamento(s) disponível(is) para você. Acesse a seção de treinamentos para começar a aprender e crescer conosco!",
                    'tipo' => Informes::TIPO_NOVO_TREINAMENTO,
                    'usuario_id' => $usuario->id,
                    'referencia_id' => null,
                    'referencia_tipo' => null,
                    'data_evento' => now(),
                    'dados_adicionais' => [
                        'total_treinamentos' => $totalTreinamentos,
                        'usuario_novo' => true,
                        'data_cadastro' => $usuario->created_at
                    ]
                ]);
            }
        } catch (\Exception $e) {
            // Log do erro mas não impede o cadastro do usuário
            // \Log::error('Erro ao criar informe de treinamentos disponíveis: ' . $e->getMessage());
        }
    }
}
