<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()
            ->where('type', 'user')
            ->orderBy('id')
            ->first();

        if (! $user) {
            $this->command?->warn('No user found. Create a user before running ServiceSeeder.');

            return;
        }

        $serviceNames = [
            'Website Development',
            'SEO Consulting',
            'Social Media Management',
            'Business Consulting',
            'Interior Design',
            'Home Cleaning',
            'AC Repair & Service',
            'Photography Package',
            'Event Planning',
            'Digital Marketing Audit',
            'Logo Design',
            'Content Writing',
            'Tax Filing Support',
            'Legal Consultation',
            'Fitness Training',
            'Catering Service',
            'Plumbing Repair',
            'Electrical Installation',
            'Car Detailing',
            'Wedding Planning',
        ];

        $productNames = [
            'Wireless Earbuds',
            'Smart LED Bulb',
            'Office Chair',
            'Laptop Stand',
            'Premium Notebook',
            'Water Purifier Filter',
            'Kitchen Mixer Jar',
            'Yoga Mat',
            'Bluetooth Speaker',
            'Power Bank 20000mAh',
            'Cotton T-Shirt Pack',
            'Stainless Steel Bottle',
            'Desk Organizer',
            'Wall Clock',
            'USB-C Hub',
            'Face Wash Kit',
            'Herbal Tea Pack',
            'Phone Case',
            'Travel Backpack',
            'Ceramic Dinner Set',
        ];

        $descriptions = [
            'High-quality offering tailored for local businesses and homes.',
            'Professional grade option with clear deliverables and support.',
            'Popular choice among JustGoom customers this season.',
            'Reliable, affordable, and ready for your next project.',
            'Includes consultation, delivery notes, and follow-up support.',
            'Ideal for startups, retailers, and growing brands.',
            'Crafted with attention to detail and practical everyday use.',
            'Dummy catalog item for pagination and listing tests.',
        ];

        $now = Carbon::now();
        $rows = [];
        $total = 800;

        for ($i = 1; $i <= $total; $i++) {
            $isProduct = $i % 3 === 0;
            $type = $isProduct ? 'product' : 'service';
            $names = $isProduct ? $productNames : $serviceNames;
            $baseName = $names[($i - 1) % count($names)];

            $priceOptions = [
                (string) (($i % 50) * 100 + 499),
                (string) (($i % 40) * 250 + 999),
                (($i % 50) * 100 + 1499).'+',
                null,
            ];

            $createdAt = $now->copy()->subDays($i % 120)->subMinutes($i % 60);

            $rows[] = [
                'user_id' => $user->id,
                'type' => $type,
                'product_name' => $baseName.' #'.$i,
                'product_image' => null,
                'product_desc' => $descriptions[($i - 1) % count($descriptions)],
                'price' => $priceOptions[$i % count($priceOptions)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'deleted_at' => null,
            ];

            if (count($rows) === 100) {
                Service::insert($rows);
                $rows = [];
            }
        }

        if ($rows !== []) {
            Service::insert($rows);
        }

        $this->command?->info("Seeded {$total} dummy services for user #{$user->id} ({$user->email}).");
    }
}
