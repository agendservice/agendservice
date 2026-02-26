<template>
  <div class="px-5 py-3">
    <div class="flex flex-wrap justify-between items-end gap-y-2 gap-x-4">
      <div class="flex items-start">
        <div class="text-2xl font-bold text-gray-800 dark:text-gray-100 mr-2">{{ titulo }}</div>
      </div>
    </div>
  </div>
  <div class="grow">
    <canvas ref="canvas" :width="width" :height="height"></canvas>
  </div>
</template>

<script>
import { Chart, BarController, BarElement, LinearScale, TimeScale, Tooltip, Legend } from 'chart.js';
import 'chartjs-adapter-moment';
import { chartColors } from './ChartjsConfig.js';
import { useDark } from '@vueuse/core';

Chart.register(BarController, BarElement, LinearScale, TimeScale, Tooltip, Legend)

export default {
  name: 'GraficoBarra',
  props: ['width', 'height', 'titulo'],
  data() {
    return {
      canvas: null,
      chart: null,
      darkMode: useDark(),
    };
  },
  beforeDestroy() {
    if (this.chart) {
      this.chart.destroy();
    }
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
    formatar (context) {
      return context[0].dataset.label;
    },
    formatarData (data) {
      return data;
    },
    initializeChart(chartData) {
      const ctx = this.canvas.getContext('2d');
      this.chart = new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
          scales: {
            y: {
              stacked: false,
              border: {
                display: false,
              },
              beginAtZero: true,
              ticks: {
                maxTicksLimit: 50,
                callback: (value) => value,
                color: this.isDarkMode ? chartColors.textColor.dark : chartColors.textColor.light,
              },
              grid: {
                color: this.isDarkMode ? chartColors.gridColor.dark : chartColors.gridColor.light,
              },  
            },
            x: {
              type: 'time',
              time: {
                parser: 'DD/MM/YYYY',
                unit: 'day',
                displayFormats: { day: 'DD/MM/YY' },
              },
              border: { display: false },
              grid: { display: false },
            },
          },
          plugins: {
            legend: { 
              display: true,
            },
            tooltip: {
              callbacks: {
                label: (context) => context.parsed.y,
              },
              titleColor: this.isDarkMode ? chartColors.tooltipBodyColor.dark : chartColors.tooltipBodyColor.light,
              bodyColor: this.isDarkMode ? chartColors.tooltipBodyColor.dark : chartColors.tooltipBodyColor.light,
              backgroundColor: this.isDarkMode ? chartColors.tooltipBgColor.dark : chartColors.tooltipBgColor.light,
              borderColor: this.isDarkMode ? chartColors.tooltipBorderColor.dark : chartColors.tooltipBorderColor.light,
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
