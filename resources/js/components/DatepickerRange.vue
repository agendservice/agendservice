<template>
  <label class="block text-sm font-medium mb-1" :for="alt">{{label}} <span class="text-red-500" v-show="required">*</span></label>
  <div class="relative">
    <flat-pickr class="form-input pl-9 dark:bg-gray-800 text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100 font-medium w-full" :config="config" v-model="date"></flat-pickr>
    <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
      <svg class="fill-current text-gray-400 dark:text-gray-500 ml-3" width="16" height="16" viewBox="0 0 16 16">
        <path d="M5 4a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2H5Z" />
        <path d="M4 0a4 4 0 0 0-4 4v8a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4H4ZM2 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4Z" />
      </svg>
    </div>    
  </div>
</template>

<script>
import flatPickr from 'vue-flatpickr-component'

export default {
  name: 'DatepickerRange',
  props: {
    label: {
      type: String,
      required: true,
    },
    alt: {
      type: String,
      default: 'default'
    },
    required: {
      type: Boolean,
      default: false,
    },
    modelValue: {
      default: null
    }
  },
  mounted () {
    if (this.modelValue) {
      this.date = this.stringToDate(this.modelValue);
    }
  },
  methods: {
    stringToDate: function (value) {
      let valorFormatado = value.split(' ~ ');
      let datas = valorFormatado.map((e) => {
        let [d, m, y] = e.split('/').map(Number);

        return new Date(y, m-1, d);
      });

      return datas;
    },
    dateToString: function (dates) {
      let formattedDates = dates.map(date => {
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();

        return `${day}/${month}/${year}`;
      });
      return formattedDates.join(' ~ ');
    }
  },
  emits: ['update:modelValue'],
  data () {
    return {
      date: null,
      config: {
        mode: 'range',
        monthSelectorType: 'static',
        dateFormat: 'd/m/Y',
        defaultDate: null,
        prevArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M5.4 10.8l1.4-1.4-4-4 4-4L5.4 0 0 5.4z" /></svg>',
        nextArrow: '<svg class="fill-current" width="7" height="11" viewBox="0 0 7 11"><path d="M1.4 10.8L0 9.4l4-4-4-4L1.4 0l5.4 5.4z" /></svg>',
        onReady: (selectedDates, dateStr, instance) => {
          instance.element.value = dateStr.replace('to', '~');
        },
        onChange: (selectedDates, dateStr, instance) => {
          instance.element.value = dateStr.replace('to', '~');
        },
      },                
    }
  },  
  components: {
    flatPickr
  },
  watch: {
    date(newValue) {
      if ((typeof newValue) === 'string') {
        this.$emit('update:modelValue', newValue);
      } else if ((typeof newValue) === 'object') {
        this.$emit('update:modelValue', this.dateToString(newValue));
      }
    }
  }
}
</script>