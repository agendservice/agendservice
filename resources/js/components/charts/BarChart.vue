
<template>
  <canvas ref="canvas"></canvas>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  name: 'BarChart',
  props: {
    chartData: {
      type: Object,
      required: true
    },
    chartOptions: {
      type: Object,
      default: () => ({
        responsive: true,
        maintainAspectRatio: false
      })
    }
  },
  setup(props) {
    const canvas = ref(null);
    let chart = null;

    onMounted(() => {
      if (canvas.value) {
        chart = new ChartJS(canvas.value, {
          type: 'bar',
          data: props.chartData,
          options: props.chartOptions
        });
      }
    });

    watch(() => props.chartData, (newData) => {
      if (chart) {
        chart.data = newData;
        chart.update();
      }
    });

    return {
      canvas
    };
  }
};
</script>
