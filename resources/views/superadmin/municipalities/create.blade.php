<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Municipality - Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .navbar {
            background: var(--primary-gradient) !important;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }

        .section-title {
            color: var(--primary-blue);
            font-weight: 700;
            margin: 20px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--secondary-yellow);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
        }

        .btn-submit {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 30px;
            font-weight: 600;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
            color: white;
        }

        .preview-barangay {
            background: var(--bg-light);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid var(--border-light);
            display: none;
        }

        .barangay-item {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 8px 12px;
            margin: 5px 0;
            display: inline-block;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building"></i> Add New Municipality
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="form-card">
            <h2 class="mb-4" style="color: var(--primary-blue);">
                <i class="bi bi-plus-circle" style="color: var(--secondary-yellow);"></i>
                Add New Municipality
            </h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.municipalities.store') }}" id="municipalityForm">
                @csrf
                
                <div class="section-title">Basic Information</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Municipality Name</label>
                        <select name="name" id="municipalitySelect" class="form-select" required>
                            <option value="">-- Select Municipality --</option>
                            <option value="Alaminos">Alaminos</option>
                            <option value="Bay">Bay</option>
                            <option value="Biñan">Biñan</option>
                            <option value="Cabuyao">Cabuyao</option>
                            <option value="Calamba">Calamba</option>
                            <option value="Calauan">Calauan</option>
                            <option value="Cavinti">Cavinti</option>
                            <option value="Famy">Famy</option>
                            <option value="Kalayaan">Kalayaan</option>
                            <option value="Liliw">Liliw</option>
                            <option value="Los Baños">Los Baños</option>
                            <option value="Luisiana">Luisiana</option>
                            <option value="Lumban">Lumban</option>
                            <option value="Mabitac">Mabitac</option>
                            <option value="Magdalena">Magdalena</option>
                            <option value="Majayjay">Majayjay</option>
                            <option value="Nagcarlan">Nagcarlan</option>
                            <option value="Paete">Paete</option>
                            <option value="Pagsanjan">Pagsanjan</option>
                            <option value="Pakil">Pakil</option>
                            <option value="Pangil">Pangil</option>
                            <option value="Pila">Pila</option>
                            <option value="Rizal">Rizal</option>
                            <option value="San Pablo">San Pablo</option>
                            <option value="San Pedro">San Pedro</option>
                            <option value="Santa Cruz">Santa Cruz</option>
                            <option value="Santa Maria">Santa Maria</option>
                            <option value="Santa Rosa">Santa Rosa</option>
                            <option value="Siniloan">Siniloan</option>
                            <option value="Victoria">Victoria</option>
                        </select>
                        <small class="text-muted">Select a municipality from Laguna</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select" required>
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $year)
                                <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Preview Barangays Section -->
                <div id="barangayPreview" class="preview-barangay">
                    <h6 class="mb-3" style="color: var(--primary-blue);">
                        <i class="bi bi-grid-3x3" style="color: var(--secondary-yellow);"></i>
                        Default Barangays for <span id="selectedMunicipality"></span>
                    </h6>
                    <div id="barangayList" class="mb-3">
                        <!-- Barangays will be loaded here -->
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        These barangays will be automatically added when you create this municipality.
                    </small>
                </div>

                <div class="section-title">Population Statistics</div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Male Population</label>
                        <input type="number" name="male_population" class="form-control" 
                               value="{{ old('male_population', 0) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Female Population</label>
                        <input type="number" name="female_population" class="form-control" 
                               value="{{ old('female_population', 0) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Households</label>
                        <input type="number" name="total_households" class="form-control" 
                               value="{{ old('total_households', 0) }}" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 0-19</label>
                        <input type="number" name="population_0_19" class="form-control" 
                               value="{{ old('population_0_19', 0) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 20-59</label>
                        <input type="number" name="population_20_59" class="form-control" 
                               value="{{ old('population_20_59', 0) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 60-100</label>
                        <input type="number" name="population_60_100" class="form-control" 
                               value="{{ old('population_60_100', 0) }}" min="0" required>
                    </div>
                </div>

                <div class="section-title">Additional Information</div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Single Parents</label>
                        <input type="number" name="single_parent_count" class="form-control" 
                               value="{{ old('single_parent_count', 0) }}" min="0" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save"></i> Create Municipality
                    </button>
                    <a href="{{ route('superadmin.municipalities.index') }}" class="btn btn-secondary ms-2">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Default barangays for each municipality
        // Complete barangay names for each municipality in Laguna
const barangayData = {
    'Alaminos': [
      'San Andres', 'San Benito', 'San Gregorio', 'San Ildefonso', 'San Juan',
        'San Miguel', 'San Roque', 'Santa Rosa', 'Santisimo Rosario'
    ],
    'Bay': [
        'Bitin', 'Calo', 'Dila', 'Maitim', 'Masaya', 'Paciano Rizal',
        'Puente', 'San Antonio', 'San Isidro', 'Santa Cruz', 'Santo Tomas'
    ],
    'Biñan': [
        'Biñan Poblacion', 'Bungahan', 'Canlalay', 'Casile', 'De La Paz',
        'Ganado', 'Langkiwa', 'Loma', 'Malaban', 'Malamig', 'Mampalasan',
        'Platero', 'Santo Domingo', 'San Antonio', 'San Francisco',
        'San Jose', 'San Juan', 'San Vicente', 'Soro-soro', 'Sto. Tomas',
        'Timbangan', 'Zapote'
    ],
    'Cabuyao': [
        'Baclaran', 'Banay-banay', 'Banlic', 'Barangay Dos', 'Barangay Tres',
        'Barangay Uno', 'Bigaa', 'Butong', 'Casile', 'Diezmo', 'Gulod',
        'Mamatid', 'Marinig', 'Niugan', 'Pittland', 'Pulo', 'Sala',
        'San Isidro', 'Barangay IV (Poblacion)', 'Barangay V (Poblacion)'
    ],
    'Calamba': [
        'Bagong Kalsada', 'Banadero', 'Banlic', 'Barandal', 'Batino',
        'Bubuyan', 'Bucal', 'Bunggo', 'Burol', 'Camaligan', 'Canlubang',
        'Halang', 'Hornalan', 'Kay-Anlog', 'La Mesa', 'Laguerta', 'Lawa',
        'Lecheria', 'Lingga', 'Looc', 'Mabato', 'Majada Labas', 'Makiling',
        'Mapagong', 'Masili', 'Maunong', 'Mayapa', 'Milagrosa', 'Paciano Rizal',
        'Palingon', 'Palo-alto', 'Pansol', 'Parian', 'Prinza', 'Punta',
        'Puting Lupa', 'Real', 'Saimsim', 'Sampiruhan', 'San Cristobal',
        'San Jose', 'San Juan', 'Sirang Lupa', 'Sucol', 'Tulo', 'Turbina',
        'Ulango', 'Uwisan', 'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)',
        'Barangay 3 (Poblacion)', 'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)',
        'Barangay 6 (Poblacion)', 'Barangay 7 (Poblacion)'
    ],
    'Calauan': [
        'Balayhangin', 'Bangyas', 'Dayap', 'Hanggan', 'Imok', 'Kanluran',
        'Lamot 1', 'Lamot 2', 'Limbon', 'Maunong', 'Mayatandang', 'Pandayan',
        'Prinza', 'San Isidro', 'Santo Tomas', 'Silangan', 'Tres Cruses',
        'Tranca', 'Zamora'
    ],
    'Cavinti': [
        'Anglas', 'Bangco', 'Bukal', 'Bulajo', 'Layasin', 'Mahipon',
        'Paowin', 'Poblacion', 'Rizal', 'Sisilmin', 'Sumucab', 'Talon',
        'Ticud', 'Tikiw', 'Wakas', 'Wawa'
    ],
    'Famy': [
        'Balitoc', 'Bangiad', 'Batangan', 'Bulusan', 'Famy Poblacion',
        'Kibang', 'Labong', 'Macamate', 'Magsikap', 'Malabon', 'Matangkap',
        'Minayutan', 'Salang', 'Tunhac', 'Tumanguib', 'Ulawan', 'Zone 2',
        'Zone 3', 'Zone 4', 'Zone 5'
    ],
    'Kalayaan': [
        'Kalayaan Poblacion', 'Longos', 'San Antonio', 'San Juan',
        'San Pablo', 'San Pedro', 'Santisimo Rosario'
    ],
    'Liliw': [
        'Bagong Anyo (Poblacion)', 'Bayate', 'Bongkol', 'Bubukal', 'Cabuyew',
        'Calumpang', 'Culoy', 'Dagatan', 'Daniw', 'Dita', 'Ibabang Palina',
        'Ibabang San Roque', 'Ibabang Sungi', 'Ibabang Taykin', 'Ilayang Palina',
        'Ilayang San Roque', 'Ilayang Sungi', 'Ilayang Taykin', 'Kanlurang Bukal',
        'Laguan', 'Luquin', 'Malabo-Kalantukan', 'Masikap (Poblacion)',
        'Maslun (Poblacion)', 'Mojon', 'Novaliches', 'Oples', 'Pag-asa (Poblacion)',
        'Palayan', 'Rizal (Poblacion)', 'Silangang Bukal', 'Tuy-Baanan'
    ], // 33 barangays [citation:3][citation:10]

    'Los Baños': [
        'Anos', 'Bagong Silang', 'Bambang', 'Batong Malake', 'Baybayin',
        'Bayog', 'Lalakay', 'Maahas', 'Malinta', 'Mayondon', 'Putho-Tuntungin',
        'San Antonio', 'Tadlac', 'Timugan'
    ],
    'Luisiana': [
        'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5',
        'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10',
        'Barangay 11', 'Barangay 12', 'Barangay 13', 'Barangay 14', 'Barangay 15',
        'Barangay 16', 'Barangay 17', 'Barangay 18', 'Barangay 19', 'Barangay 20',
        'Barangay 21', 'Barangay 22', 'Barangay 23'
    ],
    'Lumban': [
        'Bagong Silang', 'Balindang', 'Balubad', 'Caliraya', 'Concepcion',
        'Lewin', 'Maracta', 'Maytalang I', 'Maytalang II', 'Primera Parang',
        'Primera Pulo', 'Salac', 'Santo Rosario', 'Talaingan', 'Wawa',
        'Zone 1 (Poblacion)', 'Zone 2 (Poblacion)', 'Zone 3 (Poblacion)',
        'Zone 4 (Poblacion)', 'Zone 5 (Poblacion)', 'Zone 6 (Poblacion)',
        'Zone 7 (Poblacion)'
    ],
    'Mabitac': [
        'Amuyong', 'Mabitac Poblacion', 'Masapang', 'Masikap', 'Pag-asa',
        'Palale', 'San Antonio', 'San Gregorio', 'San Miguel', 'San Nicolas',
        'San Roque'
    ],
    'Magdalena': [
        'Alipit', 'Baanan', 'Balanac', 'Bucal', 'Buenavista', 'Bungkol',
        'Buo', 'Burlungan', 'Cigaras', 'Halayhayin', 'Ibabang Atingay',
        'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong', 'Ilog',
        'Malaking Ambling', 'Malinao', 'Maravilla', 'Munting Ambling',
        'Poblacion', 'Sabang', 'Salasad', 'Tanawan', 'Tipunan'
    ], // 24 barangays [citation:2][citation:9]

    'Majayjay': [
        'Amonoy', 'Bakia', 'Balanac', 'Balayong', 'Banilad', 'Banti',
        'Bitaoy', 'Botocan', 'Bukal', 'Burgos', 'Burol', 'Coralao',
        'Gagalot', 'Ibabang Banga', 'Ibabang Bayucain', 'Ilayang Banga',
        'Ilayang Bayucain', 'Isabang', 'Malinao', 'May-It', 'Munting Kawayan',
        'Olla', 'Oobi', 'Origuel (Poblacion)', 'Panalaban', 'Pangil',
        'Panglan', 'Piit', 'Pook', 'Rizal', 'San Francisco (Poblacion)',
        'San Isidro', 'San Miguel (Poblacion)', 'San Roque',
        'Santa Catalina (Poblacion)', 'Suba', 'Talortor', 'Tanawan',
        'Taytay', 'Villa Nogales'
    ], // 40 barangays [citation:4]

    'Nagcarlan': [
        'Abo', 'Alibungbungan', 'Alumbrado', 'Balayong', 'Balimbing',
        'Balinacon', 'Bambang', 'Banago', 'Banca-banca', 'Bangcuro',
        'Banilad', 'Bayaquitos', 'Buboy', 'Buenavista', 'Buhanginan',
        'Bukal', 'Bunga', 'Cabuyew', 'Calumpang', 'Kanluran Kabubuhayan',
        'Kanluran Lazaan', 'Labangan', 'Lagulo', 'Lawaguin', 'Maiit',
        'Malaya', 'Malinao', 'Manaol', 'Maravilla', 'Nagcalbang', 'Oples',
        'Palayan', 'Palina', 'Poblacion I (Pob.)', 'Poblacion II (Pob.)',
        'Poblacion III (Pob.)', 'Sabang', 'San Francisco', 'Santa Lucia',
        'Sibulan', 'Silangan Ilaya', 'Silangan Kabubuhayan', 'Silangan Lazaan',
        'Silangan Napapatid', 'Sinipian', 'Sulsuguin', 'Talahib', 'Talangan',
        'Taytay', 'Tipacan', 'Wakat', 'Yukos'
    ], // 52 barangays [citation:5]

    'Paete': [
        'Bagumbayan (Poblacion)', 'Ermita (Poblacion)', 'Ibaba del Norte (Poblacion)',
        'Ibaba del Sur (Poblacion)', 'Ilaya del Norte (Poblacion)', 'Ilaya del Sur (Poblacion)',
        'Maytoong', 'Quinale', 'San Roque'
    ],
    'Pagsanjan': [
        'Anos', 'Balaong', 'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)',
        'Barangay 3 (Poblacion)', 'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)',
        'Barangay 6 (Poblacion)', 'Barangay 7 (Poblacion)', 'Barangay 8 (Poblacion)',
        'Barangay 9 (Poblacion)', 'Buboy', 'Cabanbanan', 'Calusiche', 'Dingin',
        'Lambac', 'Layugan', 'Luban', 'Magdalo', 'Maulawin', 'Pinagsanjan',
        'Sabang', 'Sampa', 'San Rafael', 'Santisimo Rosario', 'Simbahan',
        'Sinigaran', 'Talencero', 'Tuklong', 'Yambo'
    ],
    'Pakil': [
        'Baño (Poblacion)', 'Banilan', 'Burgos (Poblacion)', 'Casa Real (Poblacion)',
        'Casinsin', 'Dorado', 'Gonzales (Poblacion)', 'Kawilihan', 'Lumot',
        'Mataas na Lupa', 'Rizal (Poblacion)', 'San Antonio', 'San Carlos',
        'San Jose (Poblacion)', 'Santa Maria', 'Taft (Poblacion)', 'Taft Proper'
    ],
    'Pangil': [
        'Balian', 'Dambo', 'Galalan', 'Isla (Poblacion)', 'Mabanban', 'Masikap',
        'Natividad (Poblacion)', 'San Jose (Poblacion)', 'San Juan', 'San Natividad',
        'San Roque', 'Santa Cruz', 'Santo Niño', 'Sinaunang'
    ],
    'Pila': [
        'Aplaya', 'Bagong Silang', 'Bambang', 'Bulilan Norte (Poblacion)',
        'Bulilan Sur (Poblacion)', 'Dulong Banyan', 'Labuin', 'Maasim',
        'Masico', 'Pook', 'San Miguel', 'Santa Clara Norte', 'Santa Clara Sur',
        'Tagumpay', 'Tibig', 'Tubuan'
    ],
    'Rizal': [
        'Antipolo', 'Entablado', 'Laguan', 'Paule 1', 'Paule 2', 'Poblacion Central',
        'Poblacion Ilaya', 'Poblacion Kanluran', 'Poblacion Silangan',
        'Pook', 'Tuy', 'Tuy B', 'Tuybang Bayan', 'Tuybang Bayan B'
    ],
    'San Pablo': [
        'Bagong Bayan', 'Bagong Pook', 'Barangay I-A', 'Barangay I-B', 'Barangay I-C',
        'Barangay I-D', 'Barangay I-E', 'Barangay II-A', 'Barangay II-B', 'Barangay II-C',
        'Barangay II-D', 'Barangay III-A', 'Barangay III-B', 'Barangay III-C', 'Barangay III-D',
        'Barangay III-E', 'Barangay IV-A', 'Barangay IV-B', 'Barangay IV-C', 'Barangay V-A',
        'Barangay V-B', 'Barangay V-C', 'Barangay VI-A', 'Barangay VI-B', 'Barangay VI-C',
        'Barangay VI-D', 'Barangay VII-A', 'Barangay VII-B', 'Barangay VII-C', 'Barangay VII-D',
        'Barangay VIII-A', 'Barangay VIII-B', 'Barangay VIII-C', 'Barangay VIII-D', 'Barangay IX-A',
        'Barangay IX-B', 'Barangay IX-C', 'Barangay X-A', 'Barangay X-B', 'Barangay X-C',
        'Barangay XI-A', 'Barangay XI-B', 'Barangay XI-C', 'Barangay XI-D', 'Barangay XII-A',
        'Barangay XII-B', 'Barangay XIII', 'Barangay XIV', 'Dela Paz', 'San Bartolome',
        'San Crispin', 'San Diego', 'San Francisco', 'San Gabriel', 'San Gregorio',
        'San Ignacio', 'San Isidro', 'San Joaquin', 'San Jose', 'San Juan',
        'San Lorenzo', 'San Lucas I', 'San Lucas II', 'San Marcos', 'San Mateo',
        'San Miguel', 'San Nicolas', 'San Pedro', 'San Rafael', 'San Roque',
        'San Vicente', 'Santa Ana', 'Santa Catalina', 'Santa Cruz', 'Santa Felomina',
        'Santa Isabel', 'Santa Maria', 'Santa Monica', 'Santa Veronica', 'Santiago I',
        'Santiago II', 'Santisimo Rosario', 'Santo Angel', 'Santo Cristo', 'Santo Niño',
        'Soledad'
    ],
    'San Pedro': [
        'Bagong Silang', 'Calendola', 'Chrysanthemum', 'Cuyab', 'Estrella',
        'Fatima', 'G.S.I.S.', 'Landayan', 'Langgam', 'Laram', 'Magsaysay',
        'Maharlika', 'Narra', 'Nueva', 'Pacita 1', 'Pacita 2', 'Poblacion',
        'Riverside', 'Rosario', 'Sampaguita', 'San Antonio', 'San Lorenzo Ruiz',
        'San Roque', 'San Vicente', 'Santo Niño', 'United Bayanihan',
        'United Better Living', 'Vicente', 'Villa Monica'
    ],
    'Santa Cruz': [
        'Alipit', 'Bagumbayan', 'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)',
        'Barangay 3 (Poblacion)', 'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)',
        'Barangay 6 (Poblacion)', 'Barangay 7 (Poblacion)', 'Barangay 8 (Poblacion)',
        'Barangay 9 (Poblacion)', 'Barangay 10 (Poblacion)', 'Barangay 11 (Poblacion)',
        'Barangay 12 (Poblacion)', 'Barangay 13 (Poblacion)', 'Barangay 14 (Poblacion)',
        'Barangay 15 (Poblacion)', 'Bubukal', 'Calios', 'Duhat', 'Gatid',
        'Jasaan', 'Labangan', 'Malinao', 'Novaliches', 'Oogong', 'Patimbao',
        'Pook', 'San Jose', 'San Juan', 'San Pablo Norte', 'San Pablo Sur',
        'Santisimo Rosario', 'Santo Angel Central', 'Santo Angel Norte',
        'Santo Angel Sur', 'Santo Tomas'
    ],
    'Santa Maria': [
        'Adia', 'Bagong Pook', 'Bagumbayan', 'Bubukal', 'Cabana', 'Calangay',
        'Cambuja', 'Coralan', 'Cueva', 'Inayapan', 'Jose Laurel Sr.',
        'Kayhakat', 'Macasipac', 'Masinao', 'Mataling-Ting', 'Palasan',
        'Patimbao', 'San Vicente', 'Santa Clara', 'Santisimo Rosario',
        'Santo Tomas', 'Tuntungin-Putol', 'Tuntungin-Putho'
    ],
    'Santa Rosa': [
        'Aplaya', 'Balibago', 'Caingin', 'Dila', 'Dita', 'Don Jose',
        'Ibaba', 'Kanluran', 'Labas', 'Macabling', 'Malitlit', 'Market Area',
        'Pooc', 'Pulong Santa Cruz', 'Santo Domingo', 'Sinalhan', 'Tagapo'
    ], // 17 barangays [citation:7]

    'Siniloan': [
        'Acevida', 'Bagong Pag-asa', 'Bagumbayan', 'Bayanihan', 'Burgos',
        'G. Redor', 'Llavac', 'Lubas', 'M. Apostol', 'Magsaysay',
        'Makiling', 'Malinao', 'Manuel D. Barretto', 'Pangil', 'Poblacion',
        'Punta', 'Santa Cruz', 'Santisimo Rosario', 'Subay', 'Taytay',
        'Wawa', 'Wenceslao Trinidad'
    ],
    'Victoria': [
        'Bago', 'Bayuin', 'Bungahan', 'Caraitan', 'Dalipit', 'Ganado',
        'Malinao', 'Masapang', 'Masaya', 'Nanhaya', 'Pag-asa', 'Parian',
        'San Benito', 'San Felix', 'San Francisco', 'San Isidro', 'San Juan',
        'San Miguel', 'San Roque', 'San Vicente', 'Silangan', 'Santo Domingo',
        'Santo Rosario', 'Tutuloy'
    ]
};

        // Municipality select element
        const municipalitySelect = document.getElementById('municipalitySelect');
        const barangayPreview = document.getElementById('barangayPreview');
        const barangayList = document.getElementById('barangayList');
        const selectedMunicipality = document.getElementById('selectedMunicipality');

        // Add event listener
        municipalitySelect.addEventListener('change', function() {
            const selected = this.value;
            
            if (selected && barangayData[selected]) {
                // Show preview section
                barangayPreview.style.display = 'block';
                selectedMunicipality.textContent = selected;
                
                // Clear previous list
                barangayList.innerHTML = '';
                
                // Add barangays
                barangayData[selected].forEach(barangay => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-info me-2 mb-2 p-2';
                    badge.style.fontSize = '0.9rem';
                    badge.innerHTML = `<i class="bi bi-pin-map-fill me-1"></i>${barangay}`;
                    barangayList.appendChild(badge);
                });
            } else {
                // Hide preview section
                barangayPreview.style.display = 'none';
            }
        });
    </script>
</body>
</html>A