<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} - {{ $year }} Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --success-green: #28a745;
            --success-green-light: #E1F7E1;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-soft: #475569;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
            padding-bottom: 20px;
        }

        /* Compact Navbar */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 10px rgba(44, 62, 143, 0.15);
            padding: 8px 0;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.2rem;
            color: white !important;
        }

        .navbar-brand i {
            color: var(--secondary-yellow);
        }

        .nav-link {
            color: white !important;
            font-size: 0.9rem;
            padding: 5px 12px !important;
        }

        /* Compact Back Button */
        .back-btn {
            background: var(--bg-white);
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            border-radius: 20px;
            padding: 5px 15px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 15px;
        }

        .back-btn:hover {
            background: var(--primary-gradient);
            color: white;
        }

        /* LARGER YEAR HEADER */
        .year-header {
            background: white;
            border-radius: 20px;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .year-badge {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            padding: 10px 25px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 1.8rem; /* LARGER */
            display: inline-flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 1px;
        }

        .year-badge i {
            font-size: 1.8rem;
        }

        .year-title {
            font-size: 2.5rem; /* MUCH LARGER */
            font-weight: 800;
            color: var(--primary-blue);
            margin: 10px 0 5px 0;
            line-height: 1.2;
        }

        .date-badge {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        /* Compact Navigation */
        .year-nav {
            display: flex;
            gap: 10px;
        }

        .year-nav-btn {
            background: var(--bg-light);
            color: var(--primary-blue);
            border: 1px solid var(--border-light);
            border-radius: 30px;
            padding: 8px 18px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .year-nav-btn:hover {
            background: var(--primary-gradient);
            color: white;
        }

        .year-nav-btn.disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        /* Compact Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 15px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-card.primary {
            background: var(--primary-gradient);
            color: white;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--secondary-yellow);
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
            margin-bottom: 3px;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Program Grid */
        .program-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .program-card {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 10px 5px;
            text-align: center;
            border: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .program-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary-blue);
        }

        .program-card.primary {
            background: var(--primary-gradient);
        }

        .program-card.primary .program-name,
        .program-card.primary .program-value {
            color: white;
        }

        .program-icon {
            font-size: 1.2rem;
            color: var(--secondary-yellow);
            margin-bottom: 5px;
        }

        .program-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 3px;
            white-space: nowrap;
        }

        .program-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        /* Section Card */
        .section-card {
            background: white;
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            height: 100%;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 2px solid var(--secondary-yellow);
            padding-bottom: 8px;
        }

        .section-title i {
            color: var(--secondary-yellow);
            font-size: 1.2rem;
        }

        /* Barangay List - CLEAR LABELS */
        .barangay-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .barangay-item {
            background: var(--bg-light);
            padding: 12px;
            border-radius: 12px;
            border: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .barangay-item:hover {
            transform: translateY(-2px);
            border-color: var(--primary-blue);
        }

        .barangay-name {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1rem;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px dashed var(--border-light);
        }

        .barangay-stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 6px 0;
            font-size: 0.9rem;
        }

        .barangay-stat-item i {
            font-size: 1rem;
            width: 20px;
        }

        .barangay-stat-item .label {
            color: var(--text-soft);
            font-weight: 500;
            min-width: 85px;
        }

        .barangay-stat-item .value {
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Program Records List */
        .program-records-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .program-record-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-light);
            padding: 10px 15px;
            border-radius: 10px;
            border: 1px solid var(--border-light);
        }

        .program-record-name {
            font-weight: 600;
            color: var(--primary-blue);
            font-size: 0.95rem;
        }

        .program-record-name i {
            color: var(--secondary-yellow);
            margin-right: 5px;
        }

        .program-record-value {
            font-weight: 700;
            color: var(--success-green);
            font-size: 1.2rem;
        }

        /* Scrollable Container */
        .scrollable-content {
            max-height: 350px;
            overflow-y: auto;
            border-radius: 10px;
            padding-right: 5px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px;
            background: var(--bg-light);
            border-radius: 12px;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--primary-blue);
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 1rem;
            margin: 10px 0 0 0;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn-compact {
            padding: 8px 20px;
            font-size: 0.95rem;
            border-radius: 30px;
            font-weight: 600;
        }

        @media (max-width: 1200px) {
            .program-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .program-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .barangay-list,
            .program-records-list {
                grid-template-columns: 1fr;
            }
            .year-title {
                font-size: 2rem;
            }
            .year-badge {
                font-size: 1.5rem;
                padding: 8px 18px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-calendar"></i> {{ $municipality->name }} - Historical Data
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.yearly.index') }}">
                            <i class="bi bi-grid"></i> Overview
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <!-- Back Button -->
        <a href="{{ route('admin.yearly.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Yearly Overview
        </a>

        <!-- LARGER YEAR HEADER -->
        <div class="year-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="year-badge">
                            <i class="bi bi-calendar3"></i> {{ $year }}
                        </span>
                        @if(isset($yearlyData->created_at))
                            <span class="date-badge">
                                <i class="bi bi-clock"></i> Updated: {{ date('F d, Y', strtotime($yearlyData->created_at)) }}
                            </span>
                        @endif
                    </div>
                    <h1 class="year-title">{{ $municipality->name }} Municipality</h1>
                    <p class="text-muted mt-2" style="font-size: 1.1rem;">
                        <i class="bi bi-building"></i> Complete data for the year {{ $year }}
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="year-nav justify-content-end">
                        @if($prevYear)
                            <a href="{{ route('admin.yearly.view', $prevYear->year) }}" class="year-nav-btn">
                                <i class="bi bi-chevron-left"></i> {{ $prevYear->year }}
                            </a>
                        @endif
                        @if($nextYear)
                            <a href="{{ route('admin.yearly.view', $nextYear->year) }}" class="year-nav-btn">
                                {{ $nextYear->year }} <i class="bi bi-chevron-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="stat-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Population</div>
                    <div class="stat-value">{{ number_format($yearlyData->total_population) }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: var(--primary-blue);">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Households</div>
                    <div class="stat-value">{{ number_format($yearlyData->total_households) }}</div>
                </div>
            </div>
        </div>

        <!-- Programs Grid -->
        <div class="program-grid">
            <div class="program-card">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">4Ps</div>
                <div class="program-value">{{ number_format($yearlyData->total_4ps) }}</div>
            </div>
            <div class="program-card">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">PWD</div>
                <div class="program-value">{{ number_format($yearlyData->total_pwd) }}</div>
            </div>
            <div class="program-card">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">Senior</div>
                <div class="program-value">{{ number_format($yearlyData->total_senior) }}</div>
            </div>
            <div class="program-card">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">AICS</div>
                <div class="program-value">{{ number_format($yearlyData->total_aics) }}</div>
            </div>
            <div class="program-card">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">ESA</div>
                <div class="program-value">{{ number_format($yearlyData->total_esa) }}</div>
            </div>
            <div class="program-card">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">SLP</div>
                <div class="program-value">{{ number_format($yearlyData->total_slp) }}</div>
            </div>
            <div class="program-card primary">
                <div class="program-icon"><i class="bi bi-heart-fill"></i></div>
                <div class="program-name">Solo Parent</div>
                <div class="program-value">{{ number_format($yearlyData->total_solo_parent) }}</div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="row">
            <!-- Barangay Data - FIXED to show for ALL years -->
            <div class="col-md-6 mb-3">
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        Barangay Details ({{ $barangays ? count($barangays) : 0 }})
                        <small class="text-muted ms-auto">Fixed for {{ $municipality->name }}</small>
                    </div>

                    @if($barangays && count($barangays) > 0)
                        <div class="scrollable-content">
                            <div class="barangay-list">
                                @foreach($barangays as $barangay)
                                <div class="barangay-item">
                                    <div class="barangay-name">
                                        <i class="bi bi-pin-map-fill" style="color: var(--secondary-yellow);"></i>
                                        {{ $barangay->name }}
                                    </div>
                                    
                                    <!-- CLEAR LABELS - No abbreviations -->
                                    <div class="barangay-stat-item">
                                        <i class="bi bi-people-fill" style="color: var(--primary-blue);"></i>
                                        <span class="label">Population:</span>
                                        <span class="value">{{ number_format($barangay->male_population + $barangay->female_population) }}</span>
                                    </div>
                                    
                                    <div class="barangay-stat-item">
                                        <i class="bi bi-house-door-fill" style="color: var(--secondary-yellow);"></i>
                                        <span class="label">Households:</span>
                                        <span class="value">{{ number_format($barangay->total_households) }}</span>
                                    </div>
                                    
                                    <div class="barangay-stat-item">
                                        <i class="bi bi-person-standing" style="color: var(--success-green);"></i>
                                        <span class="label">Single Parents:</span>
                                        <span class="value">{{ number_format($barangay->single_parent_count) }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                            <p class="text-muted">No barangay data available for {{ $year }}</p>
                            <small>Barangays are fixed for {{ $municipality->name }} but data for {{ $year }} hasn't been recorded yet.</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Program Records - FIXED with clear labels -->
            <div class="col-md-6 mb-3">
                <div class="section-card">
                    <div class="section-title">
                        <i class="bi bi-file-text"></i>
                        Program Beneficiaries ({{ $year }})
                    </div>

                    <div class="program-records-list">
                        @php
                            $programTypes = [
                                '4Ps' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_4ps],
                                'PWD' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_pwd],
                                'Senior Citizen' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_senior],
                                'AICS' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_aics],
                                'ESA' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_esa],
                                'SLP' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_slp],
                                'Solo Parent' => ['icon' => 'bi-heart-fill', 'value' => $yearlyData->total_solo_parent],
                            ];
                        @endphp
                        @foreach($programTypes as $name => $data)
                            <div class="program-record-item">
                                <span class="program-record-name">
                                    <i class="bi {{ $data['icon'] }}" style="color: var(--secondary-yellow);"></i>
                                    {{ $name }}:
                                </span>
                                <span class="program-record-value">{{ number_format($data['value']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($programs && count($programs) > 0)
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Detailed program records for {{ $year }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('admin.data.municipality') }}" class="btn btn-primary btn-compact">
                <i class="bi bi-pencil"></i> Edit {{ $year }} Data
            </a>
            <a href="{{ route('admin.yearly.compare') }}" class="btn btn-outline-primary btn-compact">
                <i class="bi bi-bar-chart"></i> Compare Years
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>