<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MunicipalityManagementController extends Controller
{
    /**
     * Display all municipalities
     */
    public function index()
    {
        $municipalities = Municipality::orderBy('name')->paginate(20);
        return view('superadmin.municipalities.index', compact('municipalities'));
    }

    /**
     * Show form to create new municipality
     */
    public function create()
    {
        return view('superadmin.municipalities.create');
    }

    /**
     * Store new municipality and its barangays
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:municipalities,name',
            'total_households' => 'required|integer|min:0',
            'male_population' => 'required|integer|min:0',
            'female_population' => 'required|integer|min:0',
            'population_0_19' => 'required|integer|min:0',
            'population_20_59' => 'required|integer|min:0',
            'population_60_100' => 'required|integer|min:0',
            'single_parent_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create municipality
        $municipality = Municipality::create([
            'name' => $request->name,
            'total_households' => $request->total_households,
            'male_population' => $request->male_population,
            'female_population' => $request->female_population,
            'population_0_19' => $request->population_0_19,
            'population_20_59' => $request->population_20_59,
            'population_60_100' => $request->population_60_100,
            'single_parent_count' => $request->single_parent_count,
            'year' => $request->year,
            'created_at' => now(),
        ]);

        // Automatically add default barangays
        $defaultBarangays = $this->getDefaultBarangays($request->name);
        
        if (!empty($defaultBarangays)) {
            foreach ($defaultBarangays as $barangayName) {
                Barangay::create([
                    'municipality' => $municipality->name,
                    'name' => $barangayName,
                    'male_population' => 0,
                    'female_population' => 0,
                    'population_0_19' => 0,
                    'population_20_59' => 0,
                    'population_60_100' => 0,
                    'single_parent_count' => 0,
                    'total_households' => 0,
                    'total_approved_applications' => 0,
                    'year' => $request->year,
                ]);
            }
            
            $message = 'Municipality created successfully with ' . count($defaultBarangays) . ' barangays!';
        } else {
            $message = 'Municipality created successfully! You can now add barangays.';
        }

        return redirect()->route('superadmin.municipalities.index')
            ->with('success', $message);
    }

    /**
     * Get default barangays for a municipality
     */
    private function getDefaultBarangays($municipality)
    {
        $barangayLists = [
            'Alaminos' => [
                'San Andres', 'San Benito', 'San Gregorio', 'San Ildefonso', 'San Juan',
                'San Miguel', 'San Roque', 'Santa Rosa', 'Santisimo Rosario'
            ],
            'Bay' => [
                'Bitin', 'Calo', 'Dila', 'Maitim', 'Masaya', 'Paciano Rizal',
                'Puente', 'San Antonio', 'San Isidro', 'Santa Cruz', 'Santo Tomas'
            ],
            'Biñan' => [
                'Biñan Poblacion', 'Bungahan', 'Canlalay', 'Casile', 'De La Paz',
                'Ganado', 'Langkiwa', 'Loma', 'Malaban', 'Malamig', 'Mampalasan',
                'Platero', 'Santo Domingo', 'San Antonio', 'San Francisco',
                'San Jose', 'San Juan', 'San Vicente', 'Soro-soro', 'Sto. Tomas',
                'Timbangan', 'Zapote'
            ],
            'Cabuyao' => [
                'Baclaran', 'Banay-banay', 'Banlic', 'Barangay Dos', 'Barangay Tres',
                'Barangay Uno', 'Bigaa', 'Butong', 'Casile', 'Diezmo', 'Gulod',
                'Mamatid', 'Marinig', 'Niugan', 'Pittland', 'Pulo', 'Sala',
                'San Isidro', 'Barangay IV (Poblacion)', 'Barangay V (Poblacion)'
            ],
            'Calamba' => [
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
            'Calauan' => [
                'Balayhangin', 'Bangyas', 'Dayap', 'Hanggan', 'Imok', 'Kanluran',
                'Lamot 1', 'Lamot 2', 'Limbon', 'Maunong', 'Mayatandang', 'Pandayan',
                'Prinza', 'San Isidro', 'Santo Tomas', 'Silangan', 'Tres Cruses',
                'Tranca', 'Zamora'
            ],
            'Cavinti' => [
                'Anglas', 'Bangco', 'Bukal', 'Bulajo', 'Layasin', 'Mahipon',
                'Paowin', 'Poblacion', 'Rizal', 'Sisilmin', 'Sumucab', 'Talon',
                'Ticud', 'Tikiw', 'Wakas', 'Wawa'
            ],
            'Famy' => [
                'Balitoc', 'Bangiad', 'Batangan', 'Bulusan', 'Famy Poblacion',
                'Kibang', 'Labong', 'Macamate', 'Magsikap', 'Malabon', 'Matangkap',
                'Minayutan', 'Salang', 'Tunhac', 'Tumanguib', 'Ulawan', 'Zone 2',
                'Zone 3', 'Zone 4', 'Zone 5'
            ],
            'Kalayaan' => [
                'Kalayaan Poblacion', 'Longos', 'San Antonio', 'San Juan',
                'San Pablo', 'San Pedro', 'Santisimo Rosario'
            ],
            'Liliw' => [
                'Bagong Anyo (Poblacion)', 'Bayate', 'Bongkol', 'Bubukal', 'Cabuyew',
                'Calumpang', 'Culoy', 'Dagatan', 'Daniw', 'Dita', 'Ibabang Palina',
                'Ibabang San Roque', 'Ibabang Sungi', 'Ibabang Taykin', 'Ilayang Palina',
                'Ilayang San Roque', 'Ilayang Sungi', 'Ilayang Taykin', 'Kanlurang Bukal',
                'Laguan', 'Luquin', 'Malabo-Kalantukan', 'Masikap (Poblacion)',
                'Maslun (Poblacion)', 'Mojon', 'Novaliches', 'Oples', 'Pag-asa (Poblacion)',
                'Palayan', 'Rizal (Poblacion)', 'Silangang Bukal', 'Tuy-Baanan'
            ],
            'Los Baños' => [
                'Anos', 'Bagong Silang', 'Bambang', 'Batong Malake', 'Baybayin',
                'Bayog', 'Lalakay', 'Maahas', 'Malinta', 'Mayondon', 'Putho-Tuntungin',
                'San Antonio', 'Tadlac', 'Timugan'
            ],
            'Luisiana' => [
                'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5',
                'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10',
                'Barangay 11', 'Barangay 12', 'Barangay 13', 'Barangay 14', 'Barangay 15',
                'Barangay 16', 'Barangay 17', 'Barangay 18', 'Barangay 19', 'Barangay 20',
                'Barangay 21', 'Barangay 22', 'Barangay 23'
            ],
            'Lumban' => [
                'Bagong Silang', 'Balindang', 'Balubad', 'Caliraya', 'Concepcion',
                'Lewin', 'Maracta', 'Maytalang I', 'Maytalang II', 'Primera Parang',
                'Primera Pulo', 'Salac', 'Santo Rosario', 'Talaingan', 'Wawa',
                'Zone 1 (Poblacion)', 'Zone 2 (Poblacion)', 'Zone 3 (Poblacion)',
                'Zone 4 (Poblacion)', 'Zone 5 (Poblacion)', 'Zone 6 (Poblacion)',
                'Zone 7 (Poblacion)'
            ],
            'Mabitac' => [
                'Amuyong', 'Mabitac Poblacion', 'Masapang', 'Masikap', 'Pag-asa',
                'Palale', 'San Antonio', 'San Gregorio', 'San Miguel', 'San Nicolas',
                'San Roque'
            ],
            'Magdalena' => [
                'Alipit', 'Baanan', 'Balanac', 'Bucal', 'Buenavista', 'Bungkol',
                'Buo', 'Burlungan', 'Cigaras', 'Halayhayin', 'Ibabang Atingay',
                'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong', 'Ilog',
                'Malaking Ambling', 'Malinao', 'Maravilla', 'Munting Ambling',
                'Poblacion', 'Sabang', 'Salasad', 'Tanawan', 'Tipunan'
            ],
            'Majayjay' => [
                'Amonoy', 'Bakia', 'Balanac', 'Balayong', 'Banilad', 'Banti',
                'Bitaoy', 'Botocan', 'Bukal', 'Burgos', 'Burol', 'Coralao',
                'Gagalot', 'Ibabang Banga', 'Ibabang Bayucain', 'Ilayang Banga',
                'Ilayang Bayucain', 'Isabang', 'Malinao', 'May-It', 'Munting Kawayan',
                'Olla', 'Oobi', 'Origuel (Poblacion)', 'Panalaban', 'Pangil',
                'Panglan', 'Piit', 'Pook', 'Rizal', 'San Francisco (Poblacion)',
                'San Isidro', 'San Miguel (Poblacion)', 'San Roque',
                'Santa Catalina (Poblacion)', 'Suba', 'Talortor', 'Tanawan',
                'Taytay', 'Villa Nogales'
            ],
            'Nagcarlan' => [
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
            ],
            'Paete' => [
                'Bagumbayan (Poblacion)', 'Ermita (Poblacion)', 'Ibaba del Norte (Poblacion)',
                'Ibaba del Sur (Poblacion)', 'Ilaya del Norte (Poblacion)', 'Ilaya del Sur (Poblacion)',
                'Maytoong', 'Quinale', 'San Roque'
            ],
            'Pagsanjan' => [
                'Anos', 'Balaong', 'Barangay 1 (Poblacion)', 'Barangay 2 (Poblacion)',
                'Barangay 3 (Poblacion)', 'Barangay 4 (Poblacion)', 'Barangay 5 (Poblacion)',
                'Barangay 6 (Poblacion)', 'Barangay 7 (Poblacion)', 'Barangay 8 (Poblacion)',
                'Barangay 9 (Poblacion)', 'Buboy', 'Cabanbanan', 'Calusiche', 'Dingin',
                'Lambac', 'Layugan', 'Luban', 'Magdalo', 'Maulawin', 'Pinagsanjan',
                'Sabang', 'Sampa', 'San Rafael', 'Santisimo Rosario', 'Simbahan',
                'Sinigaran', 'Talencero', 'Tuklong', 'Yambo'
            ],
            'Pakil' => [
                'Baño (Poblacion)', 'Banilan', 'Burgos (Poblacion)', 'Casa Real (Poblacion)',
                'Casinsin', 'Dorado', 'Gonzales (Poblacion)', 'Kawilihan', 'Lumot',
                'Mataas na Lupa', 'Rizal (Poblacion)', 'San Antonio', 'San Carlos',
                'San Jose (Poblacion)', 'Santa Maria', 'Taft (Poblacion)', 'Taft Proper'
            ],
            'Pangil' => [
                'Balian', 'Dambo', 'Galalan', 'Isla (Poblacion)', 'Mabanban', 'Masikap',
                'Natividad (Poblacion)', 'San Jose (Poblacion)', 'San Juan', 'San Natividad',
                'San Roque', 'Santa Cruz', 'Santo Niño', 'Sinaunang'
            ],
            'Pila' => [
                'Aplaya', 'Bagong Silang', 'Bambang', 'Bulilan Norte (Poblacion)',
                'Bulilan Sur (Poblacion)', 'Dulong Banyan', 'Labuin', 'Maasim',
                'Masico', 'Pook', 'San Miguel', 'Santa Clara Norte', 'Santa Clara Sur',
                'Tagumpay', 'Tibig', 'Tubuan'
            ],
            'Rizal' => [
                'Antipolo', 'Entablado', 'Laguan', 'Paule 1', 'Paule 2', 'Poblacion Central',
                'Poblacion Ilaya', 'Poblacion Kanluran', 'Poblacion Silangan',
                'Pook', 'Tuy', 'Tuy B', 'Tuybang Bayan', 'Tuybang Bayan B'
            ],
            'San Pablo' => [
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
            'San Pedro' => [
                'Bagong Silang', 'Calendola', 'Chrysanthemum', 'Cuyab', 'Estrella',
                'Fatima', 'G.S.I.S.', 'Landayan', 'Langgam', 'Laram', 'Magsaysay',
                'Maharlika', 'Narra', 'Nueva', 'Pacita 1', 'Pacita 2', 'Poblacion',
                'Riverside', 'Rosario', 'Sampaguita', 'San Antonio', 'San Lorenzo Ruiz',
                'San Roque', 'San Vicente', 'Santo Niño', 'United Bayanihan',
                'United Better Living', 'Vicente', 'Villa Monica'
            ],
            'Santa Cruz' => [
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
            'Santa Maria' => [
                'Adia', 'Bagong Pook', 'Bagumbayan', 'Bubukal', 'Cabana', 'Calangay',
                'Cambuja', 'Coralan', 'Cueva', 'Inayapan', 'Jose Laurel Sr.',
                'Kayhakat', 'Macasipac', 'Masinao', 'Mataling-Ting', 'Palasan',
                'Patimbao', 'San Vicente', 'Santa Clara', 'Santisimo Rosario',
                'Santo Tomas', 'Tuntungin-Putol', 'Tuntungin-Putho'
            ],
            'Santa Rosa' => [
                'Aplaya', 'Balibago', 'Caingin', 'Dila', 'Dita', 'Don Jose',
                'Ibaba', 'Kanluran', 'Labas', 'Macabling', 'Malitlit', 'Market Area',
                'Pooc', 'Pulong Santa Cruz', 'Santo Domingo', 'Sinalhan', 'Tagapo'
            ],
            'Siniloan' => [
                'Acevida', 'Bagong Pag-asa', 'Bagumbayan', 'Bayanihan', 'Burgos',
                'G. Redor', 'Llavac', 'Lubas', 'M. Apostol', 'Magsaysay',
                'Makiling', 'Malinao', 'Manuel D. Barretto', 'Pangil', 'Poblacion',
                'Punta', 'Santa Cruz', 'Santisimo Rosario', 'Subay', 'Taytay',
                'Wawa', 'Wenceslao Trinidad'
            ],
            'Victoria' => [
                'Bago', 'Bayuin', 'Bungahan', 'Caraitan', 'Dalipit', 'Ganado',
                'Malinao', 'Masapang', 'Masaya', 'Nanhaya', 'Pag-asa', 'Parian',
                'San Benito', 'San Felix', 'San Francisco', 'San Isidro', 'San Juan',
                'San Miguel', 'San Roque', 'San Vicente', 'Silangan', 'Santo Domingo',
                'Santo Rosario', 'Tutuloy'
            ]
        ];

        return $barangayLists[$municipality] ?? [];
    }

    /**
     * Show form to add barangays for a municipality
     */
    public function showBarangays($id)
    {
        $municipality = Municipality::findOrFail($id);
        $barangays = Barangay::where('municipality', $municipality->name)->get();
        
        // Get default barangays for this municipality if it exists in our list
        $defaultBarangays = $this->getDefaultBarangays($municipality->name);
        
        return view('superadmin.municipalities.barangays', compact('municipality', 'barangays', 'defaultBarangays'));
    }

    /**
     * Store barangays for a municipality
     */
    public function storeBarangays(Request $request, $id)
    {
        $municipality = Municipality::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'barangays' => 'required|array',
            'barangays.*' => 'required|string|max:100|distinct',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $addedCount = 0;
        $skippedCount = 0;

        // Add new barangays
        foreach ($request->barangays as $barangayName) {
            // Check if barangay already exists
            $exists = Barangay::where('municipality', $municipality->name)
                ->where('name', $barangayName)
                ->exists();

            if (!$exists) {
                Barangay::create([
                    'municipality' => $municipality->name,
                    'name' => $barangayName,
                    'male_population' => 0,
                    'female_population' => 0,
                    'population_0_19' => 0,
                    'population_20_59' => 0,
                    'population_60_100' => 0,
                    'single_parent_count' => 0,
                    'total_households' => 0,
                    'total_approved_applications' => 0,
                    'year' => date('Y'),
                ]);
                $addedCount++;
            } else {
                $skippedCount++;
            }
        }

        $message = "$addedCount barangays added successfully";
        if ($skippedCount > 0) {
            $message .= ". $skippedCount barangays already exist and were skipped.";
        }

        return redirect()->route('superadmin.municipalities.barangays', $municipality->id)
            ->with('success', $message);
    }

    /**
     * Show form to edit municipality
     */
    public function edit($id)
    {
        $municipality = Municipality::findOrFail($id);
        return view('superadmin.municipalities.edit', compact('municipality'));
    }

    /**
     * Update municipality
     */
    public function update(Request $request, $id)
    {
        $municipality = Municipality::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:municipalities,name,' . $id,
            'total_households' => 'required|integer|min:0',
            'male_population' => 'required|integer|min:0',
            'female_population' => 'required|integer|min:0',
            'population_0_19' => 'required|integer|min:0',
            'population_20_59' => 'required|integer|min:0',
            'population_60_100' => 'required|integer|min:0',
            'single_parent_count' => 'required|integer|min:0',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $municipality->update([
            'name' => $request->name,
            'total_households' => $request->total_households,
            'male_population' => $request->male_population,
            'female_population' => $request->female_population,
            'population_0_19' => $request->population_0_19,
            'population_20_59' => $request->population_20_59,
            'population_60_100' => $request->population_60_100,
            'single_parent_count' => $request->single_parent_count,
            'year' => $request->year,
        ]);

        return redirect()->route('superadmin.municipalities.index')
            ->with('success', 'Municipality updated successfully!');
    }

    /**
     * Delete municipality
     */
    public function destroy($id)
    {
        $municipality = Municipality::findOrFail($id);
        
        // Delete related barangays first
        Barangay::where('municipality', $municipality->name)->delete();
        
        // Delete municipality
        $municipality->delete();

        return redirect()->route('superadmin.municipalities.index')
            ->with('success', 'Municipality deleted successfully!');
    }

    /**
     * Get all Laguna municipalities (for dropdown)
     */
    public function getLagunaMunicipalities()
    {
        $lagunaMunicipalities = [
            'Alaminos', 'Bay', 'Biñan', 'Cabuyao', 'Calamba', 'Calauan', 'Cavinti',
            'Famy', 'Kalayaan', 'Liliw', 'Los Baños', 'Luisiana', 'Lumban', 'Mabitac',
            'Magdalena', 'Majayjay', 'Nagcarlan', 'Paete', 'Pagsanjan', 'Pakil',
            'Pangil', 'Pila', 'Rizal', 'San Pablo', 'San Pedro', 'Santa Cruz',
            'Santa Maria', 'Santa Rosa', 'Siniloan', 'Victoria'
        ];

        return response()->json($lagunaMunicipalities);
    }

    /**
     * Get barangays for a specific municipality (from database or default)
     */
    public function getBarangays($municipality)
    {
        // Try to get from database first
        $barangays = Barangay::where('municipality', $municipality)
            ->select('name')
            ->distinct()
            ->get()
            ->pluck('name');

        if ($barangays->isEmpty()) {
            // Return default list
            return response()->json($this->getDefaultBarangays($municipality));
        }

        return response()->json($barangays);
    }
}