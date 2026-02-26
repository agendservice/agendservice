<template>
  <ModalBasic :modalOpen="modelValue" @close-modal="closeModal()" :title="titulo" className="bg-branco-100">
    <div :class="['px-5 pt-4 pb-1', {'pb-4': !exibirAcoes}]">
      <div class="text-sm">
        <slot />
      </div>
    </div>

    <div class="px-5 py-4" v-show="exibirAcoes">
      <div class="flex flex-wrap justify-end space-x-2">
        <botao
          :text="textoCancelar"
          v-show="exibirCancelar"
          @click.stop="closeModal()"
          color="bg-azul-700 text-white hover:bg-azul-600 rounded-lg"
        />
        <botao
          v-if="exibirLimpar"
          :text="textoLimpar"
          @click="closeModal(); $emit('submit:limpar')"
          color="bg-azul-700 text-white hover:bg-azul-600 rounded-lg"
        />
        <botao
          :disabled="disabled"
          :text="textoConfirmar"
          color="text-white bg-laranja-500 dark:hover:bg-laranja-400 hover:bg-laranja-600 rounded-lg"
          @click="closeModal(); $emit('submit:confirmar')"
        />
      </div>
    </div>
  </ModalBasic>
</template>

<script>
import ModalBasic from './base/ModalBasic.vue';
import Botao from './Botao.vue';

export default {
  components: {
    ModalBasic,
    Botao
  },
  props: {
    titulo: {
      default: 'Modal',
      type: String,
      required: true
    },
    exibirAcoes: {
      default: true,
      type: Boolean
    },
    exibirCancelar: {
      default: true,
      type: Boolean
    },
    textoCancelar: {
      default: 'Cancelar',
      type: String
    },
    textoConfirmar: {
      default: 'Confirmar',
      type: String
    },
    modelValue: {
      default: false
    },
    disabled: {
      default: false,
      type: Boolean
    },
    exibirLimpar: {
      default: false,
      type: Boolean
    },
    textoLimpar: {
      default: 'Limpar',
      type: String
    }
  },
  emits: ['update:modelValue', 'submit:confirmar', 'submit:limpar'],
  methods: {
    closeModal() {
      this.$emit('update:modelValue', false);
    }
  }
};
</script>
