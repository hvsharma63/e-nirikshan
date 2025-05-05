<script setup lang="ts">
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

interface DataPoint {
    x: string
    [key: string]: string | number
}

interface Props {
    data: DataPoint[]
    categories: string[]
}

const props = defineProps<Props>()

// Transform data into ChartJS format
const chartData = {
    labels: props.data.map(d => d.x),
    datasets: props.categories.map((cat, index) => ({
        label: cat,
        data: props.data.map(d => Number(d[cat] ?? 0)),
        borderColor: index % 2 === 0 ? 'rgba(75,192,192,1)' : 'rgba(153,102,255,1)',
        backgroundColor: index % 2 === 0 ? 'rgba(75,192,192,0.2)' : 'rgba(153,102,255,0.2)',
        tension: 0.4,
        fill: false,
    }))
}

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
        },
        title: {
            display: true,
            text: 'Line Chart',
        },
    },
}
</script>

<template>
    <div class="h-[350px]">
        <Line :data="chartData" :options="chartOptions" />
    </div>
</template>
