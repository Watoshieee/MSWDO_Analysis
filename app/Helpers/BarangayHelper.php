<?php

namespace App\Helpers;

class BarangayHelper
{
    /**
     * Get default barangays for a municipality with proper structure
     */
    public static function getDefaultBarangays($municipality)
    {
        $barangayLists = [
            'Magdalena' => [
                'Alipit', 'Malaking Ambling', 'Munting Ambling', 'Baanan', 'Balanac',
                'Bucal', 'Buenavista', 'Bungkol', 'Buo', 'Burlungan', 'Cigaras',
                'Ibabang Atingay', 'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong',
                'Ilog', 'Malinao', 'Maravilla', 'Poblacion', 'Sabang', 'Salasad',
                'Tanawan', 'Tipunan', 'Halayhayin'
            ],
            'Liliw' => [
                'Bagong Anyo (Poblacion)', 'Bayate', 'Bongkol', 'Bubukal', 'Cabuyew',
                'Calumpang', 'San Isidro Culoy', 'Dagatan', 'Daniw', 'Dita',
                'Ibabang Palina', 'Ibabang San Roque', 'Ibabang Sungi', 'Ibabang Taykin',
                'Ilayang Palina', 'Ilayang San Roque', 'Ilayang Sungi', 'Ilayang Taykin',
                'Kanlurang Bukal', 'Laguan', 'Luquin', 'Malabo-Kalantukan',
                'Masikap (Poblacion)', 'Maslun (Poblacion)', 'Mojon', 'Novaliches',
                'Oples', 'Pag-asa (Poblacion)', 'Palayan', 'Rizal (Poblacion)',
                'Silangang Bukal', 'Tuy-Baanan'
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
            ]
        ];

        return $barangayLists[$municipality] ?? [];
    }

    /**
     * Get default barangays as objects for API response
     */
    public static function getDefaultBarangayObjects($municipality)
    {
        $names = self::getDefaultBarangays($municipality);
        $objects = [];
        
        foreach ($names as $index => $name) {
            $objects[] = (object)[
                'id' => $index + 1,
                'name' => $name,
                'municipality' => $municipality
            ];
        }
        
        return $objects;
    }
}