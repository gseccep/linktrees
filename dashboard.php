<?php
// ==================================================================
// FONTE DE DADOS FICTÍCIOS
// Altere os dados aqui e a página inteira será atualizada.
// ==================================================================
$clusters = [
    [
        'icon' => '🏆',
        'title' => 'Clientes Campeões',
        'subtitle' => '• Leais e de alto valor',
        'client_count' => 15230,
        'client_percentage' => 10.3,
        'avg_ticket' => 350.75,
        'avg_frequency' => 12.5,
        'gmv_percentage' => 40.2,
    ],
    [
        'icon' => '🚀',
        'title' => 'Clientes Potenciais',
        'subtitle' => '• Bom valor, pouca frequência',
        'client_count' => 45880,
        'client_percentage' => 31.0,
        'avg_ticket' => 195.50,
        'avg_frequency' => 2.1,
        'gmv_percentage' => 25.5,
    ],
    [
        'icon' => '💎',
        'title' => 'Ocasional Premium',
        'subtitle' => '• Compra rara, mas de alto valor',
        'client_count' => 8900,
        'client_percentage' => 6.0,
        'avg_ticket' => 520.00,
        'avg_frequency' => 1.2,
        'gmv_percentage' => 18.8,
    ],
    [
        'icon' => '💡',
        'title' => 'Novos e Experimentadores',
        'subtitle' => '• Baixo valor, primeira compra',
        'client_count' => 62150,
        'client_percentage' => 42.0,
        'avg_ticket' => 65.20,
        'avg_frequency' => 1.1,
        'gmv_percentage' => 9.5,
    ],
    [
        'icon' => '⏳',
        'title' => 'Clientes em Risco',
        'subtitle' => '• Inativos há muito tempo',
        'client_count' => 15840,
        'client_percentage' => 10.7,
        'avg_ticket' => 110.80,
        'avg_frequency' => 3.5, // Frequência histórica
        'gmv_percentage' => 6.0,
    ],
];


// ==================================================================
// PREPARAÇÃO DOS DADOS PARA OS GRÁFICOS (JAVASCRIPT)
// ==================================================================
$chart_labels = [];
$chart_client_percentages = [];
$chart_gmv_percentages = [];

foreach ($clusters as $index => $cluster) {
    // Adiciona o número do cluster ao título para o gráfico
    $chart_labels[] = 'Cluster ' . $index . ' (' . $cluster['client_percentage'] . '%)';
    $chart_client_percentages[] = $cluster['client_percentage'];
    $chart_gmv_percentages[] = $cluster['gmv_percentage'];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Segmentação de Clientes ClickBus</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* SEU CSS COMPLETO AQUI (sem alterações) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; text-align: center; }
        .header h1 { font-size: 2.5rem; font-weight: 700; margin-bottom: 10px; }
        .header p { font-size: 1.1rem; opacity: 0.9; }
        .dashboard-content { padding: 40px; }
        .charts-section { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 50px; }
        .chart-container { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e8ecf4; height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .chart-title { font-size: 1.3rem; font-weight: 600; color: #2d3748; margin-bottom: 25px; text-align: center; }
        .clusters-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }
        .cluster-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 5px solid; transition: transform 0.3s ease, box-shadow 0.3s ease; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 450px; width: 100%; }
        .cluster-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .cluster-0 { border-top-color: #4299e1; }
        .cluster-1 { border-top-color: #48bb78; }
        .cluster-2 { border-top-color: #ed8936; }
        .cluster-3 { border-top-color: #9f7aea; }
        .cluster-4 { border-top-color: #38b2ac; }
        .cluster-header { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; flex-shrink: 0; }
        .cluster-icon-row { display: flex; flex-direction: column; align-items: center; justify-content: center; margin-bottom: 15px; }
        .cluster-icon { width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; font-size: 1.5rem; flex-shrink: 0; }
        .cluster-0 .cluster-icon { background: #bee3f8; }
        .cluster-1 .cluster-icon { background: #c6f6d5; }
        .cluster-2 .cluster-icon { background: #fbd38d; }
        .cluster-3 .cluster-icon { background: #d6bcfa; }
        .cluster-4 .cluster-icon { background: #b2f5ea; }
        .cluster-main-title { font-size: 1.1rem; font-weight: 700; color: #1a202c; text-align: center; margin-bottom: 8px; line-height: 1.3; height: 2.6rem; display: flex; align-items: center; justify-content: center; }
        .cluster-subtitle { font-size: 0.85rem; font-weight: 500; color: #4a5568; opacity: 0.85; text-align: center; line-height: 1.3; height: 1.1rem; display: flex; align-items: center; justify-content: center; }
        .cluster-metrics { display: grid; grid-template-rows: auto auto auto; gap: 10px; width: 100%; flex: 1; align-content: center; justify-content: center; }
        .metrics-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .metrics-row-single { display: grid; grid-template-columns: 1fr; gap: 10px; }
        .metric { text-align: center; padding: 12px 8px; background: #f7fafc; border-radius: 8px; }
        .metric-value { font-size: 1.1rem; font-weight: 700; color: #2d3748; margin-bottom: 4px; }
        .metric-label { font-size: 0.75rem; color: #718096; font-weight: 500; line-height: 1.2; }
        .gmv-highlight { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; }
        .gmv-highlight .metric-value { color: white; font-size: 1.3rem; }
        .gmv-highlight .metric-label { color: rgba(255,255,255,0.9); font-size: 0.8rem; }
        @media (max-width: 1200px) { .clusters-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 900px) { .clusters-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .charts-section { grid-template-columns: 1fr; gap: 30px; } .clusters-grid { grid-template-columns: 1fr; } .header h1 { font-size: 2rem; } .dashboard-content { padding: 20px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Segmentação de Clientes – Clusters ClickBus</h1>
            <p>Análise comportamental e distribuição de valor por segmento</p>
        </div>

        <div class="dashboard-content">
            <div class="charts-section">
                <div class="chart-container">
                    <h3 class="chart-title">Distribuição de Clientes por Cluster</h3>
                    <canvas id="pieChart"></canvas>
                </div>
                
                <div class="chart-container">
                    <h3 class="chart-title">Participação no Faturamento (GMV %)</h3>
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="clusters-grid">
                <?php foreach ($clusters as $index => $cluster): ?>
                <div class="cluster-card cluster-<?php echo $index; ?>">
                    <div class="cluster-header">
                        <div class="cluster-icon-row">
                            <div class="cluster-icon"><?php echo $cluster['icon']; ?></div>
                            <div class="cluster-main-title"><?php echo $cluster['title']; ?></div>
                        </div>
                        <div class="cluster-subtitle"><?php echo $cluster['subtitle']; ?></div>
                    </div>
                    <div class="cluster-metrics">
                        <div class="metrics-row">
                            <div class="metric">
                                <div class="metric-value"><?php echo number_format($cluster['client_count'], 0, ',', '.'); ?></div>
                                <div class="metric-label">Clientes (<?php echo $cluster['client_percentage']; ?>%)</div>
                            </div>
                            <div class="metric">
                                <div class="metric-value">R$ <?php echo number_format($cluster['avg_ticket'], 2, ',', '.'); ?></div>
                                <div class="metric-label">Ticket Médio</div>
                            </div>
                        </div>
                        <div class="metrics-row-single">
                            <div class="metric">
                                <div class="metric-value"><?php echo number_format($cluster['avg_frequency'], 1, ',', '.'); ?></div>
                                <div class="metric-label">Freq. Média</div>
                            </div>
                        </div>
                        <div class="metrics-row-single">
                            <div class="metric gmv-highlight">
                                <div class="metric-value"><?php echo $cluster['gmv_percentage']; ?>%</div>
                                <div class="metric-label">Participação GMV</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // DADOS VINDOS DINAMICAMENTE DO PHP
        const clusterData = {
            labels: <?php echo json_encode($chart_labels); ?>,
            clientPercentages: <?php echo json_encode($chart_client_percentages); ?>,
            gmvPercentages: <?php echo json_encode($chart_gmv_percentages); ?>,
            colors: ['#4299e1', '#48bb78', '#ed8936', '#9f7aea', '#38b2ac']
        };

        // Gráfico de Pizza - Distribuição de Clientes
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: clusterData.labels,
                datasets: [{
                    data: clusterData.clientPercentages,
                    backgroundColor: clusterData.colors,
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { padding: 15, font: { size: 11, weight: '500' }, usePointStyle: true, pointStyle: 'circle', boxWidth: 8, boxHeight: 8 } },
                    tooltip: { callbacks: { label: function(context) { return ' ' + context.label.split('(')[0].trim() + ': ' + context.parsed + '% dos clientes'; } } }
                },
                cutout: '50%'
            }
        });

        // Gráfico de Barras - Participação no GMV
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Cluster 0', 'Cluster 1', 'Cluster 2', 'Cluster 3', 'Cluster 4'],
                datasets: [{
                    label: 'GMV %',
                    data: clusterData.gmvPercentages,
                    backgroundColor: clusterData.colors,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: function(context) { return 'GMV: ' + context.parsed.y + '%'; } } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 50, // Ajuste o máximo se houver valores maiores
                        ticks: { callback: function(value) { return value + '%'; } },
                        grid: { color: '#e2e8f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>