<template>
    <div class="cols-12 cols-md-4 cols-sm-12">
        <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ label }}
        </span>
        
        <label class="relative inline-flex items-center cursor-pointer">
            
            <input 
            type="checkbox" 
            v-model="status" 
            class="sr-only peer"
            alt="status"
            >
            
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:bg-gray-700 peer-checked:bg-purple-500"></div>
            
            <span 
            class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300"
            :class="status ? 'text-purple-500' : 'text-gray-500'"
            >
            {{ status ? 'Ativa' : 'Inativa' }}
            </span>

        </label>
        </div>
</template>

<script setup>
    import { ref, watch } from 'vue';
    const props = defineProps({
        modeloStatus: {
            type: Boolean,
            required: true
        },
        label: {
            type: String,
            required: false,
            default: ''
        }
    });
    const status = ref(props.modeloStatus);
    watch(() => props.modeloStatus, (newValue) => {
        status.value = newValue;
    });
</script>