<template>
  <div class="px-5 py-3">
    <div class="flex flex-wrap justify-between items-end gap-y-2 gap-x-4">
      <div class="flex items-start">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
          <icone v-show="icone" class="mr-1" :icone="icone"></icone>
          {{titulo}}
        </h2>
      </div>
    </div>
  </div>
  <div class="grow">
    <canvas ref="canvas" :width="width" :height="height"></canvas>
  </div>
</template>

<script>
import { Chart, LineController, LineElement, Filler, PointElement, LinearScale, TimeScale, Tooltip } from 'chart.js';
import 'chartjs-adapter-moment';
import { chartColors } from './ChartjsConfig.js';
import { useDark } from '@vueuse/core';
import Icone from '../../Icone.vue';

Chart.register(LineController, LineElement, Filler, PointElement, LinearScale, TimeScale, Tooltip);

export default {
  name: 'GraficoLinhaSimples',
  props: ['width', 'height', 'titulo', 'icone', 'prefixo'],
  components: {
    Icone
  },
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
      this.updateChartColors();

      // Observa mudanças no modo escuro
      this.$watch(() => this.darkMode.value, this.updateChartColors);
    },
    formatar (context) {
      return context[0].dataset.label;
    },
    labelFormatado (valor) {
      if (typeof this.prefixo !== 'undefined') {
        return this.prefixo+' '+valor;
      }
      return valor;
    },
    initializeChart(chartData) {
      const ctx = this.canvas.getContext('2d');
      this.chart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
          layout: {
            padding: 5
          },
          scales: {
            y: {
              border: { display: false },
              ticks: {
                maxTicksLimit: 5,
                callback: (value) => value,
                color: this.darkMode.value ? chartColors.textColor.dark : chartColors.textColor.light,
              },
              grid: {
                color: this.darkMode.value ? chartColors.gridColor.dark : chartColors.gridColor.light,
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
              ticks: {
                autoSkipPadding: 48,
                maxRotation: 0,
                color: this.darkMode.value ? chartColors.textColor.dark : chartColors.textColor.light,
              },
            },
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                title: (context) => this.formatar(context),
                label: (context) => this.labelFormatado(context.parsed.y),
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
          animation: false,
          maintainAspectRatio: false,
        }
      });
    },
    updateChartColors() {
      const colorScheme = this.isDarkMode ? chartColors : {
        ...chartColors,
        textColor: chartColors.textColor.light,
        gridColor: chartColors.gridColor.light,
      };

      // Verifique se a cor realmente mudou antes de atualizar
      if (
        this.chart.options.scales.x.ticks.color !== colorScheme.textColor ||
        this.chart.options.scales.y.ticks.color !== colorScheme.textColor ||
        this.chart.options.scales.y.grid.color !== colorScheme.gridColor
      ) {
        this.chart.options.scales.x.ticks.color = colorScheme.textColor;
        this.chart.options.scales.y.ticks.color = colorScheme.textColor;
        this.chart.options.scales.y.grid.color = colorScheme.gridColor;
        this.chart.options.plugins.tooltip.bodyColor = colorScheme.tooltipBodyColor;
        this.chart.options.plugins.tooltip.backgroundColor = colorScheme.tooltipBgColor;
        this.chart.options.plugins.tooltip.borderColor = colorScheme.tooltipBorderColor;
        this.chart.update('none');
      }
    }
  },
};
</script>
