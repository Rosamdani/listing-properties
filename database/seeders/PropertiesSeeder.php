<?php

namespace Database\Seeders;

use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Models\Properties;
use App\Models\PropertyImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [
            [
                'name' => 'Villa Mewah dengan Kolam Renang',
                'type' => PropertyType::HOUSE,
                'price' => 8500000000, // 8.5 Milyar Rupiah
                'location' => 'Seminyak, Bali',
                'latitude' => '-8.6804991',
                'longitude' => '115.1592218',
                'bedrooms' => 5,
                'bathrooms' => 4,
                'garages' => 2,
                'area' => 450,
                'furnished' => true,
                'available_from' => now()->addDays(30),
                'description' => 'Villa mewah dengan kolam renang pribadi, ruang tamu luas, dan taman yang indah. Lokasinya strategis dekat dengan pantai dan pusat Seminyak. Cocok untuk keluarga atau yang suka mengadakan pesta.',
                'status' => PropertyStatus::FOR_SALE,
                'featured' => true,
                'amenities' => ['pool', 'garden', 'security', 'air_conditioning', 'gym'],
                'contact_name' => 'Budi Santoso',
                'contact_email' => 'budi@example.com',
                'contact_phone' => '+62 812-3456-7890',
            ],
            [
                'name' => 'Apartemen Modern di Pusat Kota',
                'type' => PropertyType::APARTMENT,
                'price' => 15000000, // 15 Juta Rupiah per bulan
                'location' => 'Sudirman, Jakarta Selatan',
                'latitude' => '-6.2241484',
                'longitude' => '106.8093859',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'garages' => 1,
                'area' => 120,
                'furnished' => true,
                'available_from' => now()->addDays(15),
                'description' => 'Apartemen bergaya modern di jantung ibukota. Dilengkapi dengan langit-langit tinggi, jendela besar, dan peralatan rumah tangga berteknologi terbaru. Akses mudah ke pusat bisnis dan perbelanjaan.',
                'status' => PropertyStatus::FOR_RENT,
                'featured' => true,
                'amenities' => ['elevator', 'security', 'air_conditioning', 'gym', 'parking'],
                'contact_name' => 'Siti Rahma',
                'contact_email' => 'siti@example.com',
                'contact_phone' => '+62 813-2345-6789',
            ],
            [
                'name' => 'Kondominium Tepi Pantai',
                'type' => PropertyType::APARTMENT,
                'price' => 3500000000, // 3.5 Milyar Rupiah
                'location' => 'Nusa Dua, Bali',
                'latitude' => '-8.7969989',
                'longitude' => '115.2288811',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'garages' => 1,
                'area' => 180,
                'furnished' => false,
                'available_from' => now()->addDays(45),
                'description' => 'Kondominium indah tepi pantai dengan pemandangan laut yang menakjubkan. Hanya beberapa langkah dari pantai dan dekat dengan restoran dan pusat perbelanjaan.',
                'status' => PropertyStatus::FOR_SALE,
                'featured' => false,
                'amenities' => ['pool', 'beach_access', 'security', 'air_conditioning', 'balcony'],
                'contact_name' => 'Agus Wijaya',
                'contact_email' => 'agus@example.com',
                'contact_phone' => '+62 812-3456-9012',
            ],
            [
                'name' => 'Rumah Keluarga di Perumahan Elite',
                'type' => PropertyType::HOUSE,
                'price' => 25000000, // 25 Juta Rupiah per bulan
                'location' => 'Bintaro, Tangerang Selatan',
                'latitude' => '-6.2654631',
                'longitude' => '106.7112952',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'garages' => 2,
                'area' => 280,
                'furnished' => false,
                'available_from' => now()->addDays(10),
                'description' => 'Rumah keluarga yang luas di lingkungan perumahan yang tenang dan aman. Dilengkapi dengan halaman belakang yang luas, dapur yang telah direnovasi, dan dekat dengan sekolah-sekolah unggulan.',
                'status' => PropertyStatus::FOR_RENT,
                'featured' => false,
                'amenities' => ['garden', 'air_conditioning', 'storage', 'patio', 'security'],
                'contact_name' => 'Dewi Kusuma',
                'contact_email' => 'dewi@example.com',
                'contact_phone' => '+62 856-7890-1234',
            ],
            [
                'name' => 'Ruang Kantor Komersial',
                'type' => PropertyType::COMMERCIAL,
                'price' => 15000000000, // 15 Milyar Rupiah
                'location' => 'SCBD, Jakarta Selatan',
                'latitude' => '-6.2267106',
                'longitude' => '106.8083979',
                'bedrooms' => 0,
                'bathrooms' => 4,
                'garages' => 10,
                'area' => 500,
                'furnished' => false,
                'available_from' => now()->addDays(60),
                'description' => 'Ruang kantor komersial premium di pusat bisnis Jakarta. Tata ruang terbuka dengan fasilitas modern dan akses transportasi yang sangat baik. Cocok untuk perusahaan berkembang yang membutuhkan lokasi strategis.',
                'status' => PropertyStatus::FOR_SALE,
                'featured' => true,
                'amenities' => ['elevator', 'security', 'air_conditioning', 'conference_room', 'parking'],
                'contact_name' => 'Rudi Hartono',
                'contact_email' => 'rudi@example.com',
                'contact_phone' => '+62 817-8901-2345',
            ],
            [
                'name' => 'Lahan Pertanian di Pedesaan',
                'type' => PropertyType::LAND,
                'price' => 2000000000, // 2 Milyar Rupiah
                'location' => 'Ubud, Bali',
                'latitude' => '-8.5194532',
                'longitude' => '115.2535693',
                'bedrooms' => 0,
                'bathrooms' => 0,
                'garages' => 0,
                'area' => 10000,
                'furnished' => false,
                'available_from' => now(),
                'description' => 'Bidang tanah indah di pedesaan Ubud yang asri. Ideal untuk membangun villa pribadi atau resor kecil. Pemandangan alam yang menakjubkan dengan suasana yang damai dan tenang.',
                'status' => PropertyStatus::FOR_SALE,
                'featured' => false,
                'amenities' => ['mountain_view', 'river', 'electricity', 'water_supply'],
                'contact_name' => 'Rina Wijaya',
                'contact_email' => 'rina@example.com',
                'contact_phone' => '+62 812-9012-3456',
            ],
            [
                'name' => 'Townhouse Premium',
                'type' => PropertyType::HOUSE,
                'price' => 4500000000, // 4.5 Milyar Rupiah
                'location' => 'Menteng, Jakarta Pusat',
                'latitude' => '-6.1967113',
                'longitude' => '106.8283333',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'garages' => 1,
                'area' => 220,
                'furnished' => true,
                'available_from' => now()->addDays(20),
                'description' => 'Townhouse mewah di kawasan elit Jakarta. Arsitektur klasik dengan sentuhan modern, perabotan berkualitas tinggi, dan lokasi strategis di pusat kota namun tetap asri dan tenang.',
                'status' => PropertyStatus::FOR_SALE,
                'featured' => true,
                'amenities' => ['garden', 'security', 'air_conditioning', 'smart_home', 'rooftop'],
                'contact_name' => 'Anton Susanto',
                'contact_email' => 'anton@example.com',
                'contact_phone' => '+62 813-3456-7890',
            ],
        ];

        foreach ($properties as $propertyData) {
            $propertyData['slug'] = Str::slug($propertyData['name']);
            $property = Properties::create($propertyData);

            // Create sample images for each property
            $imageCount = rand(3, 6);
            for ($i = 1; $i <= $imageCount; $i++) {
                $propertyImage = new PropertyImage([
                    'image_path' => "assets/images/resource/property-{$i}.jpg",
                    'is_primary' => $i === 1,
                    'sort_order' => $i,
                    'title' => "Gambar {$i} untuk {$propertyData['name']}",
                ]);
                
                $property->images()->save($propertyImage);
            }
        }
    }
}