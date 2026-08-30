<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyProfileSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()
            ->where('status', 1)
            ->with(['subCategories' => fn ($q) => $q->where('status', 1)])
            ->get();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Run CategorySeeder first.');

            return;
        }

        $profiles = [
            ['company' => 'Shree Gold Jewellers', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'tagline' => 'Premium 22K Gold Jewellery · Wholesale & Retail'],
            ['company' => 'Fixmycars Auto Care', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'tagline' => 'Trusted car service and spare parts'],
            ['company' => 'Plasma Graphics', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'tagline' => 'Packaging and branding solutions'],
            ['company' => 'Devada Jewelry', 'city' => 'Madurai', 'state' => 'Tamil Nadu', 'tagline' => 'Handcrafted gold and silver jewellery'],
            ['company' => 'Sunrise Realty', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'tagline' => 'Residential and commercial property experts'],
            ['company' => 'HealthFirst Clinic', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'tagline' => 'Multi-speciality healthcare services'],
            ['company' => 'BrightMinds Academy', 'city' => 'Bangalore', 'state' => 'Karnataka', 'tagline' => 'Coaching for school and competitive exams'],
            ['company' => 'ShopEasy Mart', 'city' => 'Delhi', 'state' => 'Delhi', 'tagline' => 'Neighbourhood retail and daily essentials'],
            ['company' => 'SpiceRoute Dining', 'city' => 'Madurai', 'state' => 'Tamil Nadu', 'tagline' => 'South Indian catering and restaurant'],
            ['company' => 'BizGrow Consultants', 'city' => 'Bangalore', 'state' => 'Karnataka', 'tagline' => 'Business growth and strategy consulting'],
            ['company' => 'GreenBuild Contractors', 'city' => 'Delhi', 'state' => 'Delhi', 'tagline' => 'Eco-friendly construction projects'],
            ['company' => 'PixelSoft IT Solutions', 'city' => 'Bangalore', 'state' => 'Karnataka', 'tagline' => 'Custom software and web development'],
            ['company' => 'TravelMate Tours', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'tagline' => 'Domestic and international tour packages'],
            ['company' => 'AgroFresh Farms', 'city' => 'Madurai', 'state' => 'Tamil Nadu', 'tagline' => 'Organic produce and farm supplies'],
            ['company' => 'StyleHub Fashion', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'tagline' => 'Wholesale and retail fashion apparel'],
            ['company' => 'CleanHome Services', 'city' => 'Bangalore', 'state' => 'Karnataka', 'tagline' => 'Professional home cleaning services'],
            ['company' => 'EventCraft Planners', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'tagline' => 'Corporate and wedding event management'],
            ['company' => 'UrbanDecor Interiors', 'city' => 'Bangalore', 'state' => 'Karnataka', 'tagline' => 'Modern interior design studio'],
            ['company' => 'SwiftLogistics', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'tagline' => 'Pan-India freight and courier services'],
            ['company' => 'EcoSolar Energy', 'city' => 'Delhi', 'state' => 'Delhi', 'tagline' => 'Solar panel installation and maintenance'],
        ];

        $firstNames = ['Raj', 'Priya', 'Amit', 'Neha', 'Vikram', 'Anita', 'Suresh', 'Kavita', 'Rohit', 'Pooja'];
        $lastNames = ['Patel', 'Sharma', 'Mehta', 'Kumar', 'Singh', 'Reddy', 'Iyer', 'Shah', 'Gupta', 'Desai'];

        foreach ($profiles as $index => $item) {
            $category = $categories[$index % $categories->count()];
            $subCategory = $category->subCategories->first();

            if (! $subCategory) {
                continue;
            }

            $slug = Str::slug($item['company']);
            $email = 'dummy.'.($index + 1).'@justgoom.test';
            $phone = '98765'.str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT);
            $fname = $firstNames[$index % count($firstNames)];
            $lname = $lastNames[$index % count($lastNames)];
            $owner = trim("{$fname} {$lname}");

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'type' => 'user',
                    'fname' => $fname,
                    'lname' => $lname,
                    'password' => Hash::make('password123'),
                    'phone' => $phone,
                    'country' => 'India',
                    'state' => $item['state'],
                    'city' => $item['city'],
                    'category_id' => $category->id,
                    'sub_category_id' => $subCategory->id,
                    'status' => 1,
                    'email_verified_at' => now(),
                    'referral_code' => strtoupper(Str::random(8)),
                ]
            );

            CompanyProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $item['company'],
                    'slug' => $this->uniqueSlug($slug, $user->id),
                    'owner_name' => $owner,
                    'tagline' => $item['tagline'],
                    'business_desc' => $item['company'].' is a verified B2B business on Just Goom, serving buyers across '.$item['city'].' and nearby regions with quality products and reliable service.',
                    'phone' => $phone,
                    'email' => $email,
                    'address' => 'Main Market, '.$item['city'],
                    'city' => $item['city'],
                    'state' => $item['state'],
                    'country' => 'India',
                    'zipcode' => '38000'.($index + 1),
                ]
            );
        }

        $this->command->info('Seeded 20 dummy business profiles.');
        $this->command->info('Login email: dummy.1@justgoom.test … dummy.20@justgoom.test');
        $this->command->info('Password for all dummy users: password123');
    }

    private function uniqueSlug(string $baseSlug, int $userId): string
    {
        $slug = $baseSlug ?: 'company-'.$userId;
        $original = $slug;
        $suffix = 1;

        while (
            CompanyProfile::where('slug', $slug)
                ->where('user_id', '!=', $userId)
                ->exists()
        ) {
            $slug = $original.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
