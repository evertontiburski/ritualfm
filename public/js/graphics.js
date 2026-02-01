// Agora, todo o código dos gráficos está dentro desta função
function inicializarGraficosDoDashboard() {
    // Verifica se os elementos dos gráficos existem na página antes de tentar renderizar
    if (!document.querySelector("#recent-activity-chart")) {
        return; // Se não for a página do dashboard, não faz nada
    }

    console.log("Inicializando gráficos do dashboard..."); // Mensagem de teste para o console

    const commonChartOptions = {
        chart: {
            toolbar: { show: false },
            zoom: { enabled: false },
            foreColor: 'var(--color-gray)'
        },
        grid: {
            borderColor: 'var(--color-light-gray-hard)',
            strokeDashArray: 4
        },
        xaxis: {
            axisBorder: { color: 'var(--color-light-gray-hard)' },
            axisTicks: { color: 'var(--color-light-gray-hard)' }
        },
        tooltip: {
            theme: 'dark'
        }
    };

    // Gráfico 1: Atividade Recente
    var optionsActivity = {
        ...commonChartOptions,
        series: [{
            name: 'Novos Posts',
            data: dadosAtividade
        }],
        chart: { ...commonChartOptions.chart, type: 'area', height: 350 },
        colors: ['var(--color-red)'],
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.1 }
        },
        dataLabels: { enabled: false },
        xaxis: {
            ...commonChartOptions.xaxis,
            categories: labelsAtividade
        }
    };
    // Limpa o gráfico anterior antes de desenhar um novo para evitar duplicatas
    document.querySelector("#recent-activity-chart").innerHTML = "";
    var chartActivity = new ApexCharts(document.querySelector("#recent-activity-chart"), optionsActivity);
    chartActivity.render();
}

// Chama a função no carregamento inicial da página
document.addEventListener('DOMContentLoaded', inicializarGraficosDoDashboard);