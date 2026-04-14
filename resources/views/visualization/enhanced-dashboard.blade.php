<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Data Visualization - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --success-green: #28a745;
            --danger-red: #C41E24;
            --info-teal: #0891b2;
            --bg-light: #F8FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
        }

        .viz-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #E2E8F0;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .viz-card:hover {
            box-shadow: 0 8px 30px rgba(44, 62, 143, 0.15);
            transform: translateY(-2px);
        }

        .viz-card-header {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .viz-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .viz-card-badge {
            background: rgba(253, 185, 19, 0.2);
            color: var(--secondary-yellow);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .viz-card-body {
            padding: 25px;
        }

        .chart-wrapper {
            position: relative;
            height: 400px;
            width: 100%;
        }

        .chart-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .chart-btn {
            background: white;
            border: 2px solid #E2E8F0;
            color: var(--primary-blue);
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-btn:hover {
            border-color: var(--primary-blue);
            background: var(--primary-blue);
            color: white;
        }

        .chart-btn.active {
            background: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            border-left: 4px solid var(--primary-blue);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--primary-blue);
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4" style="color: var(--primary-blue); font-weight: 800;">Enhanced Data Visualization</h1>

        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Population</div>
                <div class="stat-value" id="totalPop">136,280</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Beneficiaries</div>
                <div class="stat-value" id="totalBen">9,700</div>
            </div>
        </div>

        <div class="viz-card">
            <div class="viz-card-header">
                <h3 class="viz-card-title">Population Distribution</h3>
                <span class="viz-card-badge">Interactive</span>
            </div>
            <div class="viz-card-body">
                <div class="chart-controls">
                    <button class="chart-btn active" onclick="updateChart('bar')">Bar Chart</button>
                    <button class="chart-btn" onclick="updateChart('line')">Line Chart</button>
                    <button class="chart-btn" onclick="updateChart('doughnut')">Doughnut</button>
                </div>
                <div class="chart-wrapper">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/chart-config.js"></script>
    <script>
        let mainChart;
        const ctx = document.getElementById('mainChart').getContext('2d');
        
        function createChart(type = 'bar') {
            if (mainChart) mainChart.destroy();
            
            mainChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: ['Magdalena', 'Liliw', 'Majayjay'],
                    datasets: [{
                        label: 'Population',
                        data: [45230, 38950, 52100],
                        backgroundColor: type === 'doughnut' ? ['#2C3E8F', '#FDB913', '#28a745'] : '#2C3E8F',
                        borderRadius: type === 'bar' ? 8 : 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function updateChart(type) {
            document.querySelectorAll('.chart-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            createChart(type);
        }

        createChart();
    </script>
</body>
</html>
