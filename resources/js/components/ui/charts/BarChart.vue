<script setup lang="ts">
import { Bar } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

// Register Chart.js modules
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

interface DataPoint {
    x: string
    [key: string]: string | number
}

interface Props {
    data: DataPoint[],
    categories: string[],
}

const props = defineProps<Props>()

// Transform incoming data into ChartJS format
const chartData = {
    labels: props.data.map(d => d.x),
    datasets: props.categories.map((cat, index) => ({
        label: cat,
        data: props.data.map(d => Number(d[cat] ?? 0)),
        backgroundColor: index === 0 ? 'rgba(75,192,192,0.6)' : 'rgba(153,102,255,0.6)',
        borderColor: index === 0 ? 'rgba(75,192,192,1)' : 'rgba(153,102,255,1)',
        borderWidth: 1,
    }))
}

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { position: 'bottom' as const },
        title: {
            display: true,
            text: 'Department-wise Deficiencies'
        }
    },
    scales: {
        y: { beginAtZero: true }
    }
}
</script>

<template>
    <div class="h-[350px]">
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>
