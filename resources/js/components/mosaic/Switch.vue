<template>
  <div>
    <label class="flex items-center cursor-pointer relative mb-2" :class="{ 'opacity-60 cursor-not-allowed': disabled }">
      <input
        type="checkbox"
        class="sr-only peer"
        :checked="modelValue === valorAtivo"
        :disabled="disabled"
        @change="$emit('update:modelValue', $event.target.checked ? valorAtivo : valorInativo)"
      >
      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-laranja-500"></div>
      <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ label }}
      </span>
    </label>
    <p v-if="descricao" class="text-xs text-gray-400 pl-14">
      {{ descricao }}
    </p>
  </div>
  
</template>

<script setup>
const props = defineProps({
  modelValue: { type: [String, Number, Boolean, Object, null], default: 'inativo' },
  label: { type: String, default: '' },
  descricao: { type: String, default: '' },
  disabled: { type: Boolean, default: false },
  valorAtivo: { type: [String, Number, Boolean, Object, null], default: 'ativo' },
  valorInativo: { type: [String, Number, Boolean, Object, null], default: 'inativo' },
})

const emit = defineEmits(['update:modelValue'])
</script>
