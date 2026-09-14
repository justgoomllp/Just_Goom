<?php

use App\Models\Advertisement;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

Advertisement::withTrashed()
    ->where('banner_image', 'advertisement/1789358964-I8L9hgDTbSrp.png')
    ->update(['banner_image' => 'uploads/advertisements/1789358964-I8L9hgDTbSrp.png']);

Advertisement::withTrashed()
    ->get()
    ->each(function (Advertisement $ad) {
        $path = (string) $ad->banner_image;
        if (str_starts_with($path, 'uploads/advertisement/') && ! str_starts_with($path, 'uploads/advertisements/')) {
            $ad->banner_image = str_replace('uploads/advertisement/', 'uploads/advertisements/', $path);
            $ad->save();
        }
    });

echo json_encode(Advertisement::withTrashed()->get(['id', 'banner_image'])->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo PHP_EOL;
