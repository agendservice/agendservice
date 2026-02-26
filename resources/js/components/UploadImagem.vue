<template>
  <div style="position: relative">
    <overlay v-show="loader"></overlay>
    <div class="flex items-center w-full justify-center bg-gray-200 rounded-lg" style="min-height: 200px">
      <img
        v-if="image"
        :src="image"
        @click="$attrs.readonly?'':$refs.FileInput.click()"
        style="cursor: pointer; background-color: #666"
        class="max-h-64 max-w-full object-contain"

      ></img>
      <botao v-if="image" color="red" style="position: absolute; top: 5px; right: 5px" icone="mdi-trash-can-outline" @click="removerImagem()"></botao>
      <botao v-else @click="$refs.FileInput.click()" text="Buscar Imagem"></botao>
      <input ref="FileInput" type="file" style="display: none;" @change="onFileSelect" />
      <div v-if="erro" class="absolute bottom-0 left-0 text-red-500 text-sm mt-1">
        {{ erro }}
      </div>
    </div>
  </div>
</template>

<script>
import Overlay from './mosaic/Overlay.vue';
import Botao from './mosaic/Botao.vue';

export default {
  components: { Overlay, Botao },
  data() {
    return {
      mime_type: '',
      selectedFile: '',
      image: '',
      dialog: false,
      files: '',
      loader: false
    };
  },
  props: {
    modelValue: {
      default: null
    },
    pastaDestino: {
      default: 'produtos'
    },
    erro: {
      type: String,
      default: null
    }
  },
  emits: ['update:modelValue'],
  mounted() {
    if (this.modelValue) {
      this.loader = true;
      axios.get('/get_url/' + this.modelValue).then((response) => {
        this.image = response.data;
        this.loader = false;
      });
    }
  },
  methods: {
    removerImagem() {
      this.image = null;
      this.$emit('update:modelValue', null);
      this.$emit('remover');
    },
    uploadImagem(file, targetResult) {
      this.loader = true;
      let formData = new FormData();
      formData.append('file', file);
      formData.append('destination', this.pastaDestino);

      axios.post('/upload_imagem', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((response) => {
        this.image = targetResult;
        this.$emit('update:modelValue', response.data);
        this.loader = false;
      }).catch(() => {
        this.loader = false;
        alert('Erro no upload da imagem.');
      });
    },
    onFileSelect(e) {
      const file = e.target.files[0];
      this.mime_type = file.type;

      if (file && typeof FileReader === 'function') {
        const reader = new FileReader();
        reader.onload = (event) => {
          const targetResult = event.target.result;
          this.uploadImagem(file, targetResult);
        };
        reader.readAsDataURL(file);
      } else {
        alert('Sorry, FileReader API not supported');
      }
    },
  },
  watch: {
    modelValue: function (newValue) {
      if (newValue) {
        this.loader = true;
        axios.get('/get_url/' + newValue).then((response) => {
          this.image = response.data;
        }).finally(() => {
          this.loader = false;
        });
      } else {
        this.image = null;
      }
    }
  }
};
</script>