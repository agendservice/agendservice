<template>
  <div class="relative">
    <overlay v-show="loader" />

    <div 
      :class="['border-2 border-dashed rounded-xl p-4 min-h-[150px] text-center transition-colors duration-300', 
               isDragging ? 'border-violet-500 bg-violet-50' : 'border-gray-300 bg-gray-50 dark:bg-gray-800']"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onFileDrop"
    >
      <div v-if="uploadedFile" class="flex flex-col items-center justify-center h-full">
        <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-3">{{ uploadedFile.nome_original }}</p>
        <div class="flex gap-2">
          <botao color="green" text="Abrir Arquivo" @click="abrirArquivo" />
          <botao color="red" icone="mdi-trash-can-outline" @click="removerArquivo" />
        </div>
      </div>

      <div v-else-if="loader" class="flex flex-col items-center justify-center h-full">
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Enviando...</p>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
          <div class="bg-violet-600 h-2.5 rounded-full" :style="{ width: progress + '%' }"></div>
        </div>
        <p class="text-xs mt-1">{{ progress }}%</p>
      </div>

      <div v-else class="flex flex-col items-center justify-center h-full cursor-pointer" @click="triggerFileInput">
        <p class="text-gray-500">Arraste e solte o arquivo aqui</p>
        <p class="text-sm text-gray-400 my-2">ou</p>
        <botao text="Selecionar Arquivo" :disabled="loader" />
      </div>
    </div>
    
    <input ref="fileInput" type="file" class="hidden" accept="image/*,application/pdf" @change="onFileSelect" />
  </div>
</template>

<script>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import Overlay from './mosaic/Overlay.vue';
import Botao from './mosaic/Botao.vue';

export default {
  name: 'UploadArquivo',
  data() {
    return {
      loader: false,
      progress: 0,
      fileInput: null,
      isDragging: false,
      uploadedFile: null,
    };
  },
  components: {
    Overlay,
    Botao
  },
  props: {
    modelValue: { type: [String, Number], default: null },
    docTipo: { type: String, required: true }
  },
  emits: ['update:modelValue', 'sucesso', 'erro'],
  mounted() {
    this.fileInput = this.$refs.fileInput;
    if (this.modelValue) {
      this.carregarArquivoInicial(this.modelValue);
    }
  },
  watch: {
    modelValue(newId) {
      if (newId !== this.uploadedFile?.id) {
        this.carregarArquivoInicial(newId);
      }
    }
  },
  methods : {
    triggerFileInput() {
      this.fileInput.click();
    },
    onFileSelect(event) {
      const file = event.target.files[0];
      if (file) {
        this.uploadFile(file);
      }
    },
    onFileDrop(event) {
      this.isDragging = false;
      const file = event.dataTransfer.files[0];
      if (file) {
        this.uploadFile(file);
      }
    },
    uploadFile(file) {
      const formData = new FormData();
      formData.append('file', file);
      formData.append('tipo_documento', this.docTipo);

      this.loader = true;
      this.progress = 0;

      axios.post('/arquivos/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        onUploadProgress: (progressEvent) => {
          this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
        },
      })
      .then((response) => {
        this.uploadedFile = response.data;
        this.$emit('update:modelValue', response.data.id);
        this.$emit('sucesso', 'Upload realizado com sucesso!');
      })
      .catch((error) => {
        console.error('Erro no upload do arquivo.', error);
        this.$emit('erro', 'Erro no upload do arquivo.'); 
      })
      .finally(() => {
        this.loader = false;
      });
    },
    removerArquivo() {
      if (!this.uploadedFile || !this.uploadedFile.id) return;

      const arquivoId = this.uploadedFile.id;

      this.loader = true;

      axios.delete(`/arquivos/${arquivoId}`)
        .then(() => {
          this.uploadedFile = null;
          this.$emit('update:modelValue', null);
          this.$emit('sucesso', 'Arquivo removido com sucesso!');
        })
        .catch((error) => {
          console.error('Erro ao remover o arquivo.', error);
          this.$emit('erro', 'Não foi possível remover o arquivo.');
        })
        .finally(() => {
          this.loader = false;
        });
    },
    abrirArquivo() {
      if (this.uploadedFile?.public_url) {
        window.open(this.uploadedFile.public_url, '_blank');
      }
    },
    carregarArquivoInicial(id) {
      if (!id) {
        this.uploadedFile = null;
        return;
      }
      this.loader = true;
      axios.get(`/arquivos/${id}`)
        .then((response) => {
          this.uploadedFile = response.data;
        })
        .catch((error) => {
          console.error('Erro ao carregar o arquivo inicial.', error);
          this.uploadedFile = null;
          this.$emit('erro', 'Erro ao carregar o arquivo inicial.');
        })
        .finally(() => {
          this.loader = false;
        });
    },
  },

};

</script>