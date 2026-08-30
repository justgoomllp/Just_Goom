<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Industrial',
                'slug' => 'industrial',
                'icon' => '🏭',
                'subs' => [
                    'Automobile',
                    'Spares & Parts',
                    'Pharma Machine',
                    'Heavy Machinery',
                    'Tools & Equipment',
                    'Industrial Supplies',
                ],
            ],
            [
                'name' => 'Agriculture',
                'slug' => 'agriculture',
                'icon' => '🌾',
                'subs' => [
                    'Agricultural Machinery',
                    'Agrochemicals',
                    'Animal Feed',
                    'Fertilizers',
                    'Fruits',
                    'Grains & Cereals',
                    'Seeds & Plants',
                    'Vegetables',
                ],
            ],
            [
                'name' => 'Real Estate',
                'slug' => 'real-estate',
                'icon' => '🏠',
                'subs' => [
                    'Residential Property',
                    'Commercial Property',
                    'Land & Plots',
                    'Property Management',
                    'Real Estate Agents',
                ],
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'icon' => '🏥',
                'subs' => [
                    'Hospitals & Clinics',
                    'Pharmacy',
                    'Diagnostic Labs',
                    'Fitness Center',
                    'Ayurveda & Wellness',
                    'Medical Equipment',
                ],
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'icon' => '🎓',
                'subs' => [
                    'Schools',
                    'Colleges & Universities',
                    'Coaching Institutes',
                    'Online Courses',
                    'Skill Training',
                ],
            ],
            [
                'name' => 'Food & Dining',
                'slug' => 'food-dining',
                'icon' => '🍽️',
                'subs' => [
                    'Restaurants',
                    'Catering Services',
                    'Cloud Kitchen',
                    'Supermarkets',
                    'Bakery & Confectionery',
                ],
            ],
            [
                'name' => 'Automobile',
                'slug' => 'automobile',
                'icon' => '🚗',
                'subs' => [
                    'Car Dealers',
                    'Two Wheeler',
                    'Auto Repair',
                    'Car Wash & Detailing',
                    'Tyres & Batteries',
                ],
            ],
            [
                'name' => 'Business Services',
                'slug' => 'business-services',
                'icon' => '💼',
                'subs' => [
                    'Consulting',
                    'Legal Services',
                    'Accounting & Tax',
                    'HR & Recruitment',
                    'Marketing Agency',
                ],
            ],
            [
                'name' => 'Shopping & Retail',
                'slug' => 'shopping-retail',
                'icon' => '🛍️',
                'subs' => [
                    'Fashion Retail',
                    'Electronics Store',
                    'Home & Furniture',
                    'Grocery Store',
                    'E-commerce Seller',
                ],
            ],
            [
                'name' => 'Entertainment',
                'slug' => 'entertainment',
                'icon' => '🎬',
                'subs' => [
                    'Event Management',
                    'Photography',
                    'Cinemas',
                    'Music & DJ',
                    'Party & Wedding',
                ],
            ],
            [
                'name' => 'Software & IT',
                'slug' => 'software-it',
                'icon' => '💻',
                'subs' => [
                    'Software Development',
                    'Web Design',
                    'IT Support',
                    'Digital Marketing',
                    'Mobile App Development',
                ],
            ],
            [
                'name' => 'Logistics',
                'slug' => 'logistics',
                'icon' => '🚚',
                'subs' => [
                    'Courier Services',
                    'Freight & Transport',
                    'Warehousing',
                    'Packaging',
                    'Cold Chain',
                ],
            ],
            [
                'name' => 'Construction',
                'slug' => 'construction',
                'icon' => '🏗️',
                'subs' => [
                    'Building Materials',
                    'Contractors',
                    'Interior Design',
                    'Architects',
                    'Plumbing & Electrical',
                ],
            ],
            [
                'name' => 'Jewellery & Bullion',
                'slug' => 'jewellery-bullion',
                'icon' => '💎',
                'subs' => [
                    'Gold Jewellery',
                    'Diamond Jewellery',
                    'Silver Jewellery',
                    'Bullion Trading',
                    'Custom Design',
                ],
            ],
            [
                'name' => 'Manufacturing',
                'slug' => 'manufacturing',
                'icon' => '⚙️',
                'subs' => [
                    'Plastic Products',
                    'Metal Fabrication',
                    'Textile Manufacturing',
                    'Packaging Materials',
                    'OEM Production',
                ],
            ],
        ];

        foreach ($categories as $item) {
            $category = Category::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'status' => true,
                ]
            );

            foreach ($item['subs'] as $subName) {
                $subSlug = $item['slug'].'-'.Str::slug($subName);

                SubCategory::updateOrCreate(
                    ['slug' => $subSlug],
                    [
                        'category_id' => $category->id,
                        'name' => $subName,
                        'status' => true,
                    ]
                );
            }
        }
    }
}
