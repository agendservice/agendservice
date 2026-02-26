<template>
  <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative px-6">
    <overlay v-show="internalLoader"></overlay>
    <overlay v-show="loader"></overlay>
    <header class="px-5 py-4" v-show="exibirPaginacao">
      <h2 class="font-semibold text-gray-800 dark:text-gray-100">Resultados <span class="text-gray-400 dark:text-gray-500 font-medium"></span></h2>
    </header>
    <div>
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="table-auto w-full dark:text-gray-300">
          <!-- Table header -->
          <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
            <tr>
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                <div class="font-semibold text-center last:mr-5"></div>
              </th>
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
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoes">
                <div class="font-semibold text-center last:mr-5">Ações</div>
              </th>
              <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoesPersonalizadas">
                <div class="font-semibold text-center last:mr-5">Ações</div>
              </th>
            </tr>
          </thead>
          <Draggable
            :list="list" 
            tag="tbody"
            item-key="id"
            handle=".drag-handle"
            @end="$emit('end-drag')" 
            class="divide-y divide-gray-200 dark:divide-gray-700"
          >
            <template #item="{ element: linha }">
              <tr :key="linha.id" :class="linha.cor" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                  
                  <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                      <span class="drag-handle cursor-move text-gray-400 hover:text-gray-700">
                          <icone icone="mdi-drag-vertical"></icone>
                      </span>
                  </td>

                  <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirCheck">
                      <div class="flex items-center">
                          <label class="inline-flex">
                              <span class="sr-only">Select</span>
                              <input :id="linha.id" class="form-checkbox" type="checkbox" v-model="linha.checked" />
                          </label>
                      </div>
                  </td>

                  <td 
                      class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" 
                      :style="coluna.type == 'avatar' ? 'width: 1%' : ''" 
                      v-for="(coluna, iColuna) in colunas" 
                      :key="'r_'+iColuna" 
                      :class="(!exibirCheck && iColuna === 0) ? 'pl-5' : ''"
                  >
                      <div class="w-10 h-10 mx-2" v-show="coluna.type == 'avatar' && linha[coluna.value]">
                          <img class="rounded-full object-cover max-h-10 max-w-10" :src="linha[coluna.value]" width="40" height="40" />
                      </div>
                      <div class="text-left" v-show="coluna.type != 'avatar' && coluna.type != 'money' && coluna.type != 'percentual' && coluna.type != 'link' && coluna.type != 'download' && coluna.type != 'clipboard' && coluna.type != 'acao_status'">
                          <span class="text-xs">{{getNestedValue(linha, coluna.value)}}</span>
                      </div>
                      <div class="text-left" v-show="coluna.type == 'link'">
                          <span class="text-xs underline pointer" @click="abrirLink(linha[coluna.value])">{{linha[coluna.value]}}</span>
                      </div>
                      </td>
                  
                  <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px text-center" v-show="exibirAcoes">
                      <div class="flex items-center">
                          <button class="text-amber-500 hover:text-amber-600 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-md" v-show="exibirEditar" @click="editar(linha)">
                              <icone icone="mdi-square-edit-outline"></icone>
                          </button>
                          <button class="text-red-500 hover:text-red-700 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-700 p-3 rounded-md" v-show="exibirRemover" @click="remover(linha)">
                              <icone icone="mdi-delete-outline"></icone>
                          </button>
                      </div>
                  </td>
                  
                  <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoesPersonalizadas">
                      <slot name="acoes" :linha="linha"></slot>
                  </td>

              </tr>
          </template>
          </Draggable>
          
        </table>
      </div>
    </div>
    <!-- <div class="px-6 py-4">
      <div class="flex justify-center">
        <nav class="flex" role="navigation" aria-label="Navigation">
          <ul class="inline-flex text-sm font-medium -space-x-px rounded-lg shadow-sm">
            <li v-for="(link, iLink) in modelValue.meta.links" v-show="iLink > 0 && iLink < modelValue.meta.links.length-1" style="cursor: pointer" :key="iLink" @click="mudarPagina(link)">
              <span class="pagina" :class="link.active?'pagina-selecionada':''">{{link.label}}</span>
            </li>
          </ul>
        </nav>
      </div>
    </div> -->
  </div>
</template>

<script>
import axios from 'axios';
import Icone from './Icone.vue';
import Overlay from './Overlay.vue';
import Util from '../Util.vue';
import Botao from './Botao.vue';
import Draggable from 'vuedraggable';

export default {
  name: 'Tabela',
  components: {
    Icone,
    Overlay,
    Botao
  },
  props: {
    list: {
      type: Array,
      required: true
    },
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
      default: []
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
  emits: ['update:modelValue', 'editar', 'remover','end-drag', 'sucesso', 'erro', 'aprovar', 'rejeitar', 'acao-status'],
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
        this.internalLoader = true;
        axios.post(link.url, this.modelValue.filtros).then((response) => {
          let resultados = response.data;
          resultados.filtros = this.modelValue.filtros;
          this.$emit('update:modelValue', resultados);
          this.internalLoader = false;
        })
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
