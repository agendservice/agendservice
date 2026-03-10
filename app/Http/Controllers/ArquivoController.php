<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Usuario;
use App\Models\Indicacao;
use App\Models\Agendamento;
use App\Http\Resources\AgendamentoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ArquivosResource;

class ArquivoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'tipo_documento' => 'required|string',
        ]);

        $file = $request->file('file');
        $nomeOriginal = $file->getClientOriginalName();

        $path = $file->store('arquivos', 'public');

        $user_id = \Auth::user()->id;
        $indicacao = Indicacao::where('indicado_id', $user_id)
                              ->orderBy('created_at', 'desc')
                              ->first();

        $arquivo = Arquivo::create([
            'user_id'       => $user_id,
            'indicacao_id'  => $indicacao ? $indicacao->id : '1',
            'tipo_documento' => $request->input('tipo_documento'),
            'nome_original' => $nomeOriginal,
            'path'          => $path,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'status'        => 'enviado'
        ]);

        // Verificar se a etapa atual foi completada e atualizar status da indicação
        if ($indicacao) {
            $this->verificarEtapaCompleta($indicacao);
        }

        $arquivo = new ArquivosResource($arquivo);

        return response()->json($arquivo, 201);
    }

    public function show($id)
    {
        // Busca o arquivo pelo ID
        $arquivo = Arquivo::findOrFail($id);
        // Verifica se o usuário autenticado é o dono do arquivo
        $arquico = new ArquivosResource($arquivo);

        return response()->json($arquivo);
    }

    // consulta os documentos ja enviados pelo usuario
    public function meusDocumentos(Request $request)
    {
        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
        } else {
            $user_id = \Auth::user()->id;
        }

        // Busca a indicação mais recente do usuário
        $indicacao = Indicacao::where('indicado_id', $user_id)
                              ->orderBy('created_at', 'desc')
                              ->first();

        if (!$indicacao) {
            return response()->json([
                'status' => 'sem_indicacao',
                'arquivos' => [],
                'message' => 'Usuário não possui indicação.'
            ]);
        }

        $status = $indicacao->status;

        $agendamento = null;

        if ($status === INDICACAO::STATUS_MENTORIA_AGENDADA) {
            $agendamento = AgendamentoResource::make(
                Agendamento::where('indicacao_id', $indicacao->id)
                                       ->orderBy('created_at', 'desc')
                                       ->first()
            );
        }
        // Busca os arquivos pela indicação específica
        $arquivos = Arquivo::where('indicacao_id', $indicacao->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transforma os arquivos em recursos
        $arquivos = ArquivosResource::collection($arquivos);

        return response()->json([
            'id' => $indicacao->id,
            'instituicao_bancaria' => $indicacao->instituicao_bancaria,
            'status' => $status,
            'agendamento' => $agendamento ?? null,
            'indicacao_status' => $indicacao->status,
            'etapa_atual' => $indicacao->getEtapaAtual(),
            'pode_enviar_etapa_2' => $indicacao->podeEnviarEtapa2(),
            'pode_enviar_etapa_3' => $indicacao->podeEnviarEtapa3(),
            'arquivos' => $arquivos,
        ]);
    }

    public function deletar($id)
    {
        $arquivo = Arquivo::findOrFail($id);

        // Verifica se o usuário autenticado é o dono do arquivo
        if ($arquivo->user_id !== auth()->id()) {
            return response()->json([
                'error' => 'Sem permissão',
                'message' => 'Você não tem permissão para deletar este arquivo.'
            ], 403);
        }

        // Remove o arquivo do storage
        Storage::disk('public')->delete($arquivo->path);

        // Deleta o registro do banco de dados
        $arquivo->delete();

        return response()->json(['message' => 'Arquivo deletado com sucesso']);
    }

    public function analiseDocumentos()
    {
        // Verifica no banco de dados se todos os documentos foram enviados
        $user_id = \Auth::user()->id;

        // Busca a indicação mais recente do usuário
        $indicacao = Indicacao::where('indicado_id', $user_id)
                              ->orderBy('created_at', 'desc')
                              ->first();

        if (!$indicacao) {
            return response()->json([
                'erro' => 'SEM_INDICACAO',
                'message' => 'Usuário não possui indicação.'
            ], 400);
        }

        // Busca os arquivos pela indicação específica
        $arquivos = Arquivo::where('indicacao_id', $indicacao->id);

        $tiposDocumentosNecessarios = [
            'CPF',
            'CONTRATO_CONSORCIO',
            'COMPROVANTE_PAGAMENTO',
            'CONTRATO_SERVICO',
            'CERTIDAO_CIVIL',
            'COMPROVANTE_ENDERECO',
            'IMPOSTO_RENDA',
        ];

        $documentosEnviados = $arquivos->pluck('tipo_documento')->toArray();

        $faltando = array_diff($tiposDocumentosNecessarios, $documentosEnviados);

        if (empty($faltando)) {
            // Atualiza o status da indicação para 'analise' se todos os documentos foram enviados
            $indicacao->status = 'analise';
            $indicacao->save();

            return response()->json([
                'message' => 'Todos os documentos foram enviados. Sua indicação será analisada.'
            ], 200);
        } else {
            return response()->json([
                'erro' => 'DOCUMENTOS_FALTANDO',
                'faltando' => array_values($faltando),
                'message' => 'Ainda faltam documentos para serem enviados.'
            ], 400);
        }
    }

    /**
     * Verifica se uma etapa foi completada e atualiza o status da indicação
     */
    private function verificarEtapaCompleta(Indicacao $indicacao)
    {
        // Busca todos os arquivos da indicação
        $arquivos = Arquivo::where('indicacao_id', $indicacao->id)
            ->where('status', 'enviado')
            ->get();

        $tiposEnviados = $arquivos->pluck('tipo_documento')->toArray();

        // Documentos da Etapa 1 - Documentos Pessoais
        $etapa1Docs = ['CPF', 'CERTIDAO_CIVIL', 'COMPROVANTE_ENDERECO', 'IMPOSTO_RENDA'];
        $etapa1Completa = !array_diff($etapa1Docs, $tiposEnviados);

        // Documentos da Etapa 2 - Contratos
        $etapa2Docs = ['CONTRATO_CONSORCIO', 'CONTRATO_SERVICO'];
        $etapa2Completa = !array_diff($etapa2Docs, $tiposEnviados);

        // Documentos da Etapa 3 - Pagamento
        $etapa3Docs = ['COMPROVANTE_PAGAMENTO'];
        $etapa3Completa = !array_diff($etapa3Docs, $tiposEnviados);

        // Atualizar status baseado na etapa completada
        if ($etapa3Completa && $indicacao->status === Indicacao::STATUS_PAGAMENTO_PENDENTE) {
            // Etapa 3 completada - marcar pagamento para análise
            $indicacao->marcarPagamentoParaAnalise();
        } elseif ($etapa2Completa && $indicacao->status === Indicacao::STATUS_CONTRATOS_PENDENTES) {
            // Etapa 2 completada - marcar contratos para análise
            $indicacao->marcarContratosParaAnalise();
        } elseif ($etapa1Completa && $indicacao->status === Indicacao::STATUS_PENDENTE) {
            // Etapa 1 completada - marcar documentos pessoais para análise
            $indicacao->marcarDocumentosPessoaisParaAnalise();
        }
    }
}
