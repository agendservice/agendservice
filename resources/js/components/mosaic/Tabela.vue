<template>
  <div class="bg-branco-300 dark:bg-azul-500 shadow-sm rounded-xl relative px-6">
    <overlay v-show="loader"></overlay>
    <header class="px-5 py-4" v-show="exibirPaginacao">
      <h2 class="font-semibold text-gray-900 dark:text-branco-100">Resultados <span class="text-gray-800 dark:text-branco-100 font-medium">{{modelValue.meta.total}}</span></h2>
    </header>
    <div>
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="table-auto w-full dark:text-white dark:bg-azul-900">
          <!-- Table header -->
          <thead class="text-xs font-semibold uppercase text-branco-500 dark:text-white bg-azul-500 dark:bg-azul-600">
            <tr>
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirCheck">
                <div class="flex items-center">
                  <label class="inline-flex">
                    <span class="sr-only">Select all</span>
                    <input class="form-checkbox" type="checkbox" v-model="selectAll" />
                  </label>
                </div>
              </th>
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" v-for="(coluna, iColuna) in colunas" :key="'c_'+iColuna" :class="(!exibirCheck&&iColuna===0)?'pl-5':''">
                <div class="font-semibold text-left">{{coluna.text}}</div>
              </th>
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoesPersonalizadas">
                <div class="font-semibold text-center last:mr-5">Ações</div>
              </th>
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoes">
                <div class="font-semibold text-center last:mr-5">Ações</div>
              </th>
            </tr>
          </thead>
          <!-- Table body -->
          <tbody class="text-sm divide-y divide-azul-500 dark:divide-azul-700" v-if="modelValue.data">
            <tr v-show="modelValue.data.length <= 0">
              <td class="px-2 first:pl-5 last:pr-5 py-3 bg-gray-100 dark:bg-azul-700 whitespace-nowrap w-px text-center" :colspan="colunas.length+(exibirCheck?1:0)+(exibirAcoes?1:0)+(exibirAcoesPersonalizadas?1:0)">
                Nenhum resultado encontrado
              </td>
            </tr>
            <tr v-for="(resultado, iResultado) in modelValue.data" :key="iResultado" class="bg-branco-300 dark:bg-azul-500 hover:bg-branco-400 dark:hover:bg-azul-600 rounded-t-xl">
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirCheck">
                <div class="flex items-center">
                  <label class="inline-flex">
                    <span class="sr-only">Select</span>
                    <input :id="resultado.id" class="form-checkbox" type="checkbox" v-model="resultado.checked" />
                  </label>
                </div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" :style="coluna.type == 'avatar'?'width: 1%':''" v-for="(coluna, iColuna) in colunas" :key="'r_'+iColuna" :class="(!exibirCheck&&iColuna===0)?'pl-5':''">
                <div class="mx-2 h-16 w-16" v-show="coluna.type == 'avatar' && resultado[coluna.value]">
                  <img class="rounded-md object-cover h-full w-full" :src="resultado[coluna.value]" />
                </div>
                <div class="text-left" v-show="coluna.type != 'avatar' && coluna.type != 'money' && coluna.type != 'percentual' && coluna.type != 'link' && coluna.type != 'download' && coluna.type != 'clipboard' && coluna.type != 'acao_status' && coluna.type != 'data'">
                  <span class="text-xs">{{getNestedValue(resultado, coluna.value)}}</span>
                </div>
                <div class="text-left" v-show="coluna.type == 'link'">
                  <span class="text-xs underline pointer" @click="abrirLink(resultado[coluna.value])">{{resultado[coluna.value]}}</span>
                </div>
                <div class ="text-left" v-show="coluna.type == 'clipboard'">
                  <span class="text-xs pointer" @click="copiarLink(resultado[coluna.value])">
                    <span v-if="copiedRowToken === resultado[coluna.value]" class="flex items-center text-green-900 font-semibold">
                      Copiado!
                      <icone icone="mdi-check" class="ml-1"></icone>
                    </span>
                    <span v-else class="flex items-center text-indigo-950 dark:text-indigo-400 hover:text-indigo-400 dark:hover:text-indigo-100">
                      Copiar link
                      <icone icone="mdi-content-copy" class="ml-1"></icone>
                    </span>
                  </span>
                </div>
                <div class="text-left" v-show="coluna.type == 'download'">
                  <span class="text-xs underline pointer" @click="abrirLink(resultado[coluna.value])">
                    <icone icone="mdi-download"></icone>
                  </span>
                </div>
                <div class="text-left text-xs" v-show="coluna.type == 'money'">{{util.formatarDinheiro(resultado[coluna.value])}}</div>
                <div class="text-left text-xs" v-show="coluna.type == 'percentual'">{{util.formatarDecimal(resultado[coluna.value])}}%</div>
                
                <div class="text-left align-middle" v-if="coluna.type === 'acao_status'">
                  <icone 
                    :icone="coluna.options.find(opt => opt.value === resultado[coluna.value])?.icone || 'mdi-help-circle-outline'" 
                    :class="coluna.options.find(opt => opt.value === resultado[coluna.value])?.class || 'text-gray-500'" 
                  />
                  <span class="ml-1">
                    {{ coluna.options.find(opt => opt.value === resultado[coluna.value])?.label || 'Indefinido' }}
                  </span>
                </div>
                <div class="text-left text-xs" v-if="coluna.type === 'data'">
                  {{ util.formatarData(resultado[coluna.value]) }}
                </div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px text-center" v-show="exibirAcoes">
                <div class="flex items-center">

                  <button class="text-amber-500 hover:text-amber-600 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-md" v-show="exibirEditar" @click="editar(resultado)">
                    <icone icone="mdi-square-edit-outline"></icone>
                  </button>
                  <button class="text-red-500 hover:text-red-700 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-md" v-show="exibirRemover" @click="remover(resultado)">
                    <icone icone="mdi-delete-outline"></icone>
                  </button>

                </div>
              </td>
              <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoesPersonalizadas">
                <slot name="acoes" :linha="resultado"></slot>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="px-6 py-4">
      <div class="flex justify-center">
        <nav class="flex" role="navigation" aria-label="Navigation">
          <ul class="inline-flex text-sm font-medium -space-x-px rounded-lg shadow-sm">
            <li v-for="(link, iLink) in modelValue.meta.links" v-show="iLink > 0 && iLink < modelValue.meta.links.length-1" style="cursor: pointer" :key="iLink" @click="mudarPagina(link)" class="p-1">
              <span class="pagina" :class="link.active?'pagina-selecionada rounded-xl':'rounded-none'">{{link.label}}</span>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Icone from './Icone.vue';
import Overlay from './Overlay.vue';
import Util from '../Util.vue';
import Botao from './Botao.vue';
// import Botao from './mosaic/Botao.vue';

export default {
  name: 'Tabela',
  components: {
    Icone,
    Overlay,
    Botao
  },
  props: {
    urlBaseAfiliado: {
      type: String,
      default: ''
    },
    exibirPaginacao: {
      default: true
    },
    modelValue: {
      default: {
        meta: {
          total: 0
        },
      }
    },
    colunas: {
      type: Array,
      default: () => []
    },
    exibirCheck: {
      default: false
    },
    exibirAcoes: {
      default: true
    },
    exibirEditar: {
      default: true
    },
    exibirRemover: {
      default: true
    },
    exibirAcoesPersonalizadas: {
      default: false
    },
    loader: {
      default: false
    }
  },
  data () {
    return {
      copiedRowToken: null,
      selectAll: false,
      selected: [],
      internalLoader: false,
      util: Util
    }
  },
  emits: ['update:modelValue', 'editar', 'remover', 'sucesso', 'erro', 'aprovar', 'rejeitar', 'acao-status'],
  methods: {
    abrirLink: function (link) {
      window.open(link);
    },
    async copiarLink(token) {
      if (!this.urlBaseAfiliado) {
        this.$emit('erro', 'A URL base de afiliação não foi configurada.');
        return;
      }
      const urlCompleta = `${this.urlBaseAfiliado}&token=${token}`;
      if (!navigator.clipboard) {
        this.$emit('erro', 'Seu navegador não suporta a função de copiar.');
        return;
      }

      try {
        await navigator.clipboard.writeText(urlCompleta);
        this.copiedRowToken = token;
        this.$emit('sucesso', 'Link de afiliação copiado com sucesso!');
        setTimeout(() => {
          this.copiedRowToken = null;
        }, 2500);
      } catch (err) {
        console.error("Falha ao copiar o link: ", err);
        this.$emit('erro', 'Falha ao copiar o link.');
      }

    },
    mudarPagina: function (link) {
      if (link.url) {
        axios.post(link.url, this.modelValue.filtros)
        .then((response) => {
          let resultados = response.data;
          resultados.filtros = this.modelValue.filtros;
          this.$emit('update:modelValue', resultados);
        }).catch((error) => {
          this.$emit('erro', 'Erro ao carregar a página.');
        }).finally(() => {
        });
      }
    },
    checkAll: function () {
      this.modelValue.data.forEach(resultado => {
        resultado.checked = this.selectAll;
      });
    },
    editar: function (linha) {
      this.$emit('editar', linha);
    },
    remover: function (linha) {
      this.$emit('remover', linha);
    },
    aprovar: function (linha) {
      this.$emit('aprovar', linha);
    },
    rejeitar: function (linha) {
      this.$emit('rejeitar', linha);
    },
    getNestedValue(obj, path) {
      if (!path) return obj;
      return path.split('.').reduce((o, key) => (o && o[key] !== 'undefined' ? o[key] : null), obj);
    }
  },
  watch: {
    selectAll: function (val) {
      this.checkAll();
    }
  }
};
</script>
