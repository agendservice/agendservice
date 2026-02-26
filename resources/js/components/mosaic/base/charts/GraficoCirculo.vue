<template>
  <div class="px-5 py-3">
    <div class="flex flex-wrap justify-between items-end gap-y-2 gap-x-4">
      <div class="flex items-start">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
          <icone v-show="icone" :icone="icone"></icone>
          {{titulo}}
        </h2>
      </div>
      <div class="grow mb-1">
        <ul class="flex flex-wrap gap-x-4 sm:justify-end" v-if="chart">
          <li v-for="(item, iItem) in chart.data.datasets" :key="iItem">
            <span class="text-xs" style="cursor: pointer" :style="'color: '+item.borderColor">
              {{item.label}}
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="grow">
    <canvas ref="canvas" :width="width" :height="height"></canvas>
  </div>
</template>

<script>
import { Chart, DoughnutController, ArcElement, TimeScale, Tooltip } from 'chart.js'
import 'chartjs-adapter-moment';
import { chartColors } from './ChartjsConfig.js';
import { useDark } from '@vueuse/core';
import Util from '../../../Util.vue';
import Icone from '../../Icone.vue';

Chart.register(DoughnutController, ArcElement, TimeScale, Tooltip)

export default {
  name: 'GraficoCirculo',
  props: ['width', 'height', 'titulo', 'icone'],
  data() {
    return {
      canvas: null,
      chart: null,
      util: Util,
      darkMode: useDark(),
    };
  },
  beforeDestroy() {
    if (this.chart) {
      this.chart.destroy();
    }
  },
  components: {
    Icone
  },
  computed: {
    isDarkMode() {
      return this.darkMode.value;
    }
  },
  methods: {
    init(chartData) {
      if (this.chart) {
        this.chart.destroy();
      }
      this.canvas = this.$refs.canvas;
      this.initializeChart(chartData);
    },
    initializeChart(chartData) {
      const ctx = this.canvas.getContext('2d');
      this.chart = new Chart(ctx, {
        type: 'doughnut',
        data: chartData,
        options: {
          cutout: '80%',
          layout: {
            padding: 24,
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: (context) => context.parsed.y,
              },
              titleColor: this.darkMode.value ? chartColors.tooltipBodyColor.dark : chartColors.tooltipBodyColor.light,
              bodyColor: this.darkMode.value ? chartColors.tooltipBodyColor.dark : chartColors.tooltipBodyColor.light,
              backgroundColor: this.darkMode.value ? chartColors.tooltipBgColor.dark : chartColors.tooltipBgColor.light,
              borderColor: this.darkMode.value ? chartColors.tooltipBorderColor.dark : chartColors.tooltipBorderColor.light,
            },            
          },
          interaction: {
            intersect: false,
            mode: 'nearest',
          },
          animation: {
            duration: 500,
          },
          maintainAspectRatio: false,
          resizeDelay: 200,
        }
      });
    }
  },
};
</script>
