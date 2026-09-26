<?php

namespace App\Support;

class PricingCatalog
{
    public const USD_RATE = 83;

    public static function formatInr(int $amount): string
    {
        return '₹'.number_format($amount, 0);
    }

    public static function formatUsd(int $amount): string
    {
        return '$'.number_format((int) round($amount / self::USD_RATE), 0);
    }

    /**
     * @return list<string>
     */
    public static function purchasableNames(): array
    {
        return ['Silver', 'Gold', 'Platinum'];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function plans(): array
    {
        return [
            [
                'key' => 'silver',
                'name' => 'Silver',
                'icon' => '🥈',
                'inr' => 3000,
                'tagline' => 'Ideal for startups and small businesses.',
                'includes_label' => 'Includes:',
                'cta' => 'Choose Silver',
                'cta_class' => 'btn-outline-primary',
                'user_cta_class' => 'user-btn-default',
                'features' => [
                    '1 Business Listing',
                    'Business Name',
                    'Company Logo',
                    'Contact Details',
                    'Website Link',
                    'Business Description up to 500 words',
                    'Up to 10 Images',
                    '10 Products',
                    '10 Services',
                    '5 Projects',
                    'Business Hours',
                    'Google Map Location',
                    'Social Media Links',
                    'WhatsApp Contact Button',
                    'Basic SEO Indexing',
                    'Customer Inquiry Form',
                    'Renewal Reminder',
                    'Email Support',
                ],
                'limits' => [
                    'services' => '5',
                    'products' => '5',
                    'projects' => '5',
                    'videos' => '2',
                    'team' => '3',
                    'documents' => '5',
                    'articles' => '5',
                    'offers' => '2',
                    'document_size' => '5 MB',
                    'video_size' => '5 MB',
                ],
            ],
            [
                'key' => 'gold',
                'name' => 'Gold',
                'icon' => '🥇',
                'inr' => 5000,
                'popular' => true,
                'tagline' => 'Ideal for growing businesses that want more exposure.',
                'includes_label' => 'Everything in Silver, plus:',
                'cta' => 'Choose Gold',
                'cta_class' => 'btn-accent',
                'user_cta_class' => 'user-btn-primary',
                'features' => [
                    'Featured Listing in Category',
                    'Priority in Search Results',
                    'Up to 50 Images',
                    '50 Products',
                    '50 Services',
                    '25 Projects',
                    'Promotional Video',
                    'PDF Brochure Upload',
                    'Company Certificates',
                    'Team Members',
                    'Business Keywords',
                    '"Verified Business" Badge',
                    'Monthly Visitor Statistics',
                    'Click-to-Call Button',
                    'Priority Email Support',
                ],
                'limits' => [
                    'services' => '10',
                    'products' => '10',
                    'projects' => '10',
                    'videos' => '5',
                    'team' => '5',
                    'documents' => '10',
                    'articles' => '10',
                    'offers' => '5',
                    'document_size' => '5 MB',
                    'video_size' => '5 MB',
                ],
            ],
            [
                'key' => 'platinum',
                'name' => 'Platinum',
                'icon' => '💎',
                'inr' => 7000,
                'tagline' => 'Designed for businesses seeking maximum visibility and lead generation.',
                'includes_label' => 'Everything in Gold, plus:',
                'cta' => 'Choose Platinum',
                'cta_class' => 'btn-primary',
                'user_cta_class' => 'user-btn-primary',
                'features' => [
                    'Homepage Featured Listing',
                    'Premium "Top Business" Badge',
                    'Unlimited Images',
                    'Unlimited Products',
                    'Unlimited Services',
                    'Unlimited Projects',
                    'Multiple Branch Locations',
                    'Unlimited Team Members',
                    'Unlimited Videos',
                    'Unlimited PDF Catalogs',
                    'Downloadable Product Catalog',
                    'Direct WhatsApp Chat',
                    'Lead Management Dashboard',
                    'Monthly Performance Report',
                    'Premium Customer Support',
                    'New Product Highlights',
                    'New Service Highlights',
                    'Featured Projects Showcase',
                    'Special Festival Promotions',
                    'Social Media Promotion by JustGoom (2 posts per year)',
                ],
                'limits' => [
                    'services' => '20',
                    'products' => '20',
                    'projects' => '20',
                    'videos' => '10',
                    'team' => '10',
                    'documents' => '20',
                    'articles' => '20',
                    'offers' => '10',
                    'document_size' => '5 MB',
                    'video_size' => '5 MB',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function planByName(string $name): ?array
    {
        foreach (self::plans() as $plan) {
            if (strcasecmp((string) $plan['name'], $name) === 0) {
                return $plan;
            }
        }

        return null;
    }

    /**
     * @return list<list<string>>
     */
    public static function comparisonRows(): array
    {
        return [
            ['Business Listing', '1', '1', '1'],
            ['Images', '10', '50', 'Unlimited'],
            ['Featured Category Listing', '—', '✓', '✓'],
            ['Homepage Feature', '—', '—', '✓'],
            ['Verified Badge', '—', '✓', 'Premium'],
            ['Search Priority', 'Basic', 'High', 'Highest'],
            ['Visitor Analytics', '—', 'Monthly', 'Advanced'],
            ['Lead Dashboard', '—', '—', '✓'],
            ['Support', 'Email', 'Priority', 'Premium'],
        ];
    }

    /**
     * @return list<list<string>>
     */
    public static function uploadLimitRows(): array
    {
        return [
            ['Team members', '3', '5', '10'],
            ['Products and services', '5', '10', '20'],
            ['Documents', '5', '10', '20'],
            ['Projects', '5', '10', '20'],
            ['Articles', '5', '10', '20'],
            ['Videos', '2', '5', '10'],
            ['Offers', '2', '5', '10'],
            ['Document max size (PDF)', '5 MB', '5 MB', '5 MB'],
            ['Video upload max size', '5 MB', '5 MB', '5 MB'],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function addons(): array
    {
        return [
            ['name' => 'Extra Business Branch', 'inr' => 500, 'period' => '/year each'],
            ['name' => 'Additional Featured Category', 'inr' => 300, 'period' => '/year'],
            ['name' => 'Homepage Banner Advertisement', 'inr' => 2500, 'period' => '/month'],
            ['name' => 'Homepage Slider', 'inr' => 5000, 'period' => '/month'],
            ['name' => 'Sponsored Category', 'inr' => 10000, 'period' => '/month'],
            ['name' => 'Verified Business (document verification)', 'inr' => 500, 'period' => '/year'],
            ['name' => 'SEO Optimization Service', 'inr' => 2000, 'period' => ''],
            ['name' => 'Professional Business Profile Writing', 'inr' => 1000, 'period' => ''],
            ['name' => 'Product Photography', 'custom' => true],
            ['name' => 'Video Advertisement', 'custom' => true],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function databaseRecords(): array
    {
        return [
            [
                'name' => 'Silver',
                'rate' => 3000,
                'duration_days' => 365,
                'max_video_size_mb' => 5,
                'max_video_count' => 2,
                'max_project_count' => 5,
                'max_article_count' => 5,
                'max_team_count' => 3,
                'max_service_count' => 5,
                'max_document_count' => 5,
                'max_document_size_mb' => 5,
                'max_offer_count' => 2,
            ],
            [
                'name' => 'Gold',
                'rate' => 5000,
                'duration_days' => 365,
                'max_video_size_mb' => 5,
                'max_video_count' => 5,
                'max_project_count' => 10,
                'max_article_count' => 10,
                'max_team_count' => 5,
                'max_service_count' => 10,
                'max_document_count' => 10,
                'max_document_size_mb' => 5,
                'max_offer_count' => 5,
            ],
            [
                'name' => 'Platinum',
                'rate' => 7000,
                'duration_days' => 365,
                'max_video_size_mb' => 5,
                'max_video_count' => 10,
                'max_project_count' => 20,
                'max_article_count' => 20,
                'max_team_count' => 10,
                'max_service_count' => 20,
                'max_document_count' => 20,
                'max_document_size_mb' => 5,
                'max_offer_count' => 10,
            ],
        ];
    }
}
