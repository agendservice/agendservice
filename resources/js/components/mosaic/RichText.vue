<template>
  <div>
    <overlay v-show="loader"></overlay>
    <label class="block text-sm font-medium mb-1">
      {{ label }} <span class="text-red-500" v-show="required">*</span>
    </label>
    <quill-editor
      :value="valorSelecionado"
      :options="editorOptions"
      @textChange="alterado"
      @ready="onEditorReady"
      ref="rich"
      class="bg-white dark:bg-gray-800"
    />
    <div class="text-xs mt-1 text-red-500" v-show="erro">{{ erro }}</div>
  </div>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';
import Overlay from './Overlay.vue';

export default {
  components: {
    QuillEditor, Overlay
  },
  props: {
    label: {
      type: String,
      required: true,
    },
    required: {
      type: Boolean,
      default: false,
    },
    erro: {
      type: String,
      default: null,
    },
    modelValue: {
      type: String,
      default: '',
    }
  },
  data() {
    return {
      valorSelecionado: this.modelValue,
      quillInstance: null,
      loader: false,
      editorOptions: {
        theme: 'snow',
        placeholder: 'Digite aqui...',
        modules: {
          toolbar: {
            container: [
              [{ header: [1, 2, 3, 4, false] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ list: 'ordered' }, { list: 'bullet' }],
              ['link', 'image', 'clean'],
            ],
            handlers: {
              image: this.imageHandler
            },
          },
        },
      },
    };
  },
  methods: {
    onEditorReady(quill) {
      this.quillInstance = quill;
      
      // Evita sobrescrever se já estiver com o valor certo
      if (this.modelValue && quill.root.innerHTML !== this.modelValue) {
        this.setHtml(this.modelValue);
      }
    },
    alterado(event) {
      let html = this.$refs.rich.getHTML();
      this.$emit('update:modelValue', html);
    },
    setText: function (text) {
      this.$refs.rich.setText(text);
    },
    setHtml: function (htmlContent) {
      this.$refs.rich.setHTML(htmlContent);
    },
    imageHandler: function () {
      this.loader = true;
      const input = document.createElement('input');
      input.setAttribute('type', 'file');
      input.setAttribute('accept', 'image/*');
      input.click();

      input.onchange = async () => {
        const file = input.files[0];
        if (file) {
          // Faça o upload da imagem para o servidor
          let formData = new FormData();
          formData.append('file', file);
          formData.append('destination', 'imagens');

          try {
            const response = await axios.post('/upload_imagem', formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            });

            if (!this.quillInstance) {
              console.error('Quill não está inicializado.');
              return;
            }
            const range = this.quillInstance.getSelection();
            if (range) {
              let urlImagem = '/get_url/' + response.data;
              axios.get(urlImagem).then((response) => {
                let image = response.data;
                this.quillInstance.insertEmbed(range.index, 'image', image);
                this.loader = false;
              });
            } else {
              console.error('Nenhuma seleção ativa no editor.');
            }
          } catch (error) {
            console.error('Erro ao fazer upload da imagem:', error);
          }
        }
      };
    }
  }
}
</script>

<style>
/* Ajustes de estilo */
.ql-container {
  min-height: 150px;
}
</style>
