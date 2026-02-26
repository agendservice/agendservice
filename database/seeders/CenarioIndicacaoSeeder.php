<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Indicacao;
use App\Models\Meta;
use Illuminate\Support\Facades\DB;

class CenarioIndicacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Iniciando o seeder de cenário de indicações...');

        // --- SEÇÃO DE LIMPEZA MODIFICADA ---
        $this->command->warn('Limpando apenas dados de teste (Parceiros)...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Encontra os IDs dos usuários parceiros para limpar os dados relacionados
        $parceiroIds = Usuario::where('acesso_id', 1)->pluck('id');

        // 2. Apaga as indicações e metas relacionadas a esses parceiros
        Indicacao::whereIn('indicador_id', $parceiroIds)->orWhereIn('indicado_id', $parceiroIds)->delete();
        Meta::whereIn('user_id', $parceiroIds)->delete();

        // 3. Apaga APENAS os usuários parceiros (acesso_id = 1)
        Usuario::where('acesso_id', 1)->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // --- FIM DA SEÇÃO DE LIMPEZA ---

        // --- 1. Definir os Status ---
        // Lista de status que queremos distribuir entre as indicações.
        $statuses = [
            Indicacao::STATUS_PENDENTE,
            Indicacao::STATUS_DOCUMENTOS_PESSOAIS_ANALISE,
            Indicacao::STATUS_AGUARDANDO_MENTORIA,
            Indicacao::STATUS_CONTRATOS_ANALISE,
            Indicacao::STATUS_PAGAMENTO_ANALISE,
            Indicacao::STATUS_APROVADA,
        ];
        $totalStatuses = count($statuses);
        $this->command->info("Distribuindo indicações entre {$totalStatuses} status diferentes.");

        // --- 2. Criar o Usuário Indicador Principal ---
        $indicadorPrincipal = Usuario::factory()->create([
            'nome' => 'Usuário Indicador Principal',
            'email' => 'indicador@email.com',
        ]);
        $this->command->info("Usuário indicador principal criado: {$indicadorPrincipal->nome} (ID: {$indicadorPrincipal->id})");

        // --- 3. Criar uma Meta para o Indicador Principal ---
        // Todas as indicações feitas por ele estarão associadas a esta meta.
        $metaPrincipal = Meta::factory()->create([
            'user_id' => $indicadorPrincipal->id,
            'numero_meta' => 'META-PRINCIPAL-2025',
        ]);
        $this->command->info("Meta principal criada para o indicador.");

        // --- 4. Criar os 20 Usuários Indicados e suas Indicações ---
        $this->command->getOutput()->progressStart(20);

        for ($i = 1; $i <= 20; $i++) {
            // Cria o usuário indicado (Ex: usuario1@email.com)
            $indicado = Usuario::factory()->create([
                'nome' => "Usuário Indicado {$i}",
                'email' => "usuario{$i}@email.com",
            ]);

            // Cria a indicação, conectando o indicador, o indicado e a meta.
            // O status é distribuído ciclicamente pela lista de status.
            Indicacao::factory()->create([
                'indicador_id' => $indicadorPrincipal->id,
                'indicado_id' => $indicado->id,
                'meta_id' => $metaPrincipal->id,
                'status' => $statuses[($i - 1) % $totalStatuses], // Lógica para distribuir os status
            ]);
            
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Seeder de cenário de indicações concluído com sucesso!');
    }
}