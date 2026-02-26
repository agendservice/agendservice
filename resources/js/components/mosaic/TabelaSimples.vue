<template>
  <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl relative">
    <overlay v-show="internalLoader"></overlay>
    <overlay v-show="loader"></overlay>
    <div class="overflow-x-auto">
      <table class="table-auto w-full dark:text-gray-300">
        <!-- Table header -->
        <thead class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 border-t border-b border-gray-100 dark:border-gray-700/60">
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
            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoes">
              <div class="font-semibold text-center last:mr-5">Ações</div>
            </th>
            <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirAcoesPersonalizadas">
              <div class="font-semibold text-center last:mr-5">Ações</div>
            </th>
          </tr>
        </thead>
        <!-- Table body -->
        <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60" v-if="modelValue">
          <tr v-show="modelValue.length <= 0">
            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px text-center" :colspan="colunas.length+(exibirCheck?1:0)+(exibirAcoes?1:0)+(exibirAcoesPersonalizadas?1:0)">
              Nenhum resultado encontrado
            </td>
          </tr>
          <tr v-for="(resultado, iResultado) in modelValue" :key="iResultado" :class="resultado.cor">
            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" v-show="exibirCheck">
              <div class="flex items-center">
                <label class="inline-flex">
                  <span class="sr-only">Select</span>
                  <input :id="resultado.id" class="form-checkbox" type="checkbox" v-model="resultado.checked" />
                </label>
              </div>
            </td>
            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" v-for="(coluna, iColuna) in colunas" :key="'r_'+iColuna" :class="(!exibirCheck&&iColuna===0)?'pl-5':''">
              <div class="text-left" v-show="coluna.type != 'money'">{{resultado[coluna.value]}}</div>
              <div class="text-left" v-show="coluna.type == 'money'">{{util.formatarDinheiro(resultado[coluna.value])}}</div>
            </td>
            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px text-center" v-show="exibirAcoes">
              <div class="flex items-center">
                <button class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 rounded-full" @click="editar(resultado)">
                  <icone icone="mdi-square-edit-outline"></icone>
                </button>
                <button class="text-red-500 hover:text-red-600 rounded-full" @click="remover(resultado)">
                  <icone icone="mdi-delete-outline"></icone>
                </button>
              </div>
            </td>
            <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px text-center" v-show="exibirAcoesPersonalizadas">
              <div class="flex items-center">
                <slot name="acoes" :linha="resultado"></slot>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Icone from './Icone.vue';
import Overlay from './Overlay.vue';
import Util from '../Util.vue';

export default {
  name: 'Tabela',
  components: {
    Icone,
    Overlay
  },
  props: {
    modelValue: {
      default: {}
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
    exibirAcoesPersonalizadas: {
      default: false
    },
    loader: {
      default: false
    }
  },
  data () {
    return {
      selectAll: false,
      selected: [],
      internalLoader: false,
      util: Util
    }
  },
  emits: ['update:modelValue', 'editar', 'remover'],
  methods: {
    checkAll: function () {
      this.modelValue.forEach(resultado => {
        resultado.checked = this.selectAll;
      });
    },
    editar: function (linha) {
      this.$emit('editar', linha);
    },
    remover: function (linha) {
      this.$emit('remover', linha);
    }
  },
  watch: {
    selectAll: function (val) {
      this.checkAll();
    }
  }
};
</script>
