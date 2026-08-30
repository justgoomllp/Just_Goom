<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyProfileDocumentController;
use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\AuditLogController;
use App\Http\Controllers\Front\Auth\LoginController as FrontLoginController;
use App\Http\Controllers\Front\Auth\RegisterController;
use App\Http\Controllers\Front\Auth\ResendVerificationController;
use App\Http\Controllers\Front\Auth\VerifyEmailController;
use App\Http\Controllers\Front\BusinessActivityController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\DashboardController;
use App\Http\Controllers\Front\DocumentController;
use App\Http\Controllers\Front\InquiryController;
use App\Http\Controllers\Front\LocationController;
use App\Http\Controllers\Front\OfferController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\PasswordController;
use App\Http\Controllers\Front\PaymentHistoryController;
use App\Http\Controllers\Front\ProfileController;
use App\Http\Controllers\Front\ProjectController;
use App\Http\Controllers\Front\PublicProfileController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\RazorpayWebhookController;
use App\Http\Controllers\Front\SubscriptionController;
use App\Http\Controllers\Front\TeamController;
use App\Http\Controllers\Front\UserNotificationController;
use App\Http\Controllers\Front\VideoController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('front.home');

Route::get('/register/sub-categories/{category}', [RegisterController::class, 'subCategories'])->name('front.register.sub-categories');
Route::get('/register/check-slug', [RegisterController::class, 'checkSlug'])->name('front.register.check-slug');

Route::middleware('guest')->group(function () {
    Route::get('/login', [FrontLoginController::class, 'show'])->name('front.login');
    Route::post('/login', [FrontLoginController::class, 'login'])->name('front.login.submit');
    Route::get('/register', [RegisterController::class, 'show'])->name('front.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('front.register.submit');
});

Route::post('/email/verification-notification', ResendVerificationController::class)
    ->middleware('throttle:6,1')
    ->name('front.verification.send');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware('signed')
    ->name('front.verification.verify');

Route::post('/logout', [FrontLoginController::class, 'logout'])->name('front.logout');

Route::post('/razorpay/webhook', RazorpayWebhookController::class)->name('front.razorpay.webhook');

Route::get('/about', fn () => app(PageController::class)->publicPage('about'))->name('front.about');
Route::get('/articles', [ArticleController::class, 'listing'])->name('front.articles');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('front.articles.show');
Route::get('/calculators', fn () => app(PageController::class)->publicPage('calculators'))->name('front.calculators');
Route::get('/categories', [FrontCategoryController::class, 'index'])->name('front.categories');
Route::get('/category-details', fn () => app(PageController::class)->publicPage('category-details'))->name('front.category-details');
Route::get('/contact', fn () => app(PageController::class)->publicPage('contact'))->name('front.contact');
Route::get('/pricing', fn () => app(PageController::class)->publicPage('pricing'))->name('front.pricing');
Route::get('/all-profiles', [PublicProfileController::class, 'index'])->name('front.all-profiles');
Route::get('/profile', [PublicProfileController::class, 'redirectLegacy'])->name('front.profile');
Route::get('/profile/{slug}', function (string $slug) {
    return redirect('/'.$slug, 301);
})->where('slug', '[a-z0-9\-]+');

Route::prefix('users')->name('front.users.')->middleware(['auth', 'front.user', 'front.plan'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/business-activity', [BusinessActivityController::class, 'index'])->name('business-activity');
    Route::get('/analytics', fn () => app(PageController::class)->userPage('analytics'))->name('analytics');

    // Articles
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('article-form');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::patch('/articles/{article}/status', [ArticleController::class, 'updateStatus'])->name('articles.status');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');

    // Banners
    Route::get('/banners', fn () => app(PageController::class)->userPage('banners'))->name('banners');
    Route::get('/banner-form', fn () => app(PageController::class)->userPage('banner-form'))->name('banner-form');

    // Account
    Route::get('/change-password', [PasswordController::class, 'show'])->name('change-password');
    Route::put('/change-password', [PasswordController::class, 'update'])->name('change-password.update');
    Route::get('/delete', fn () => app(PageController::class)->userPage('delete'))->name('delete');

    // Documents
    Route::get('/document-add', [DocumentController::class, 'create'])->name('document-add');
    Route::redirect('/document-form', '/users/document-add')->name('document-form');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::patch('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('documents.status');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Inquiries
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries');
    Route::get('/inquiries/{inquiry}/reply', [InquiryController::class, 'reply'])->name('inquiries.reply');
    Route::post('/inquiries/{inquiry}/reply', [InquiryController::class, 'storeReply'])->name('inquiries.reply.store');
    Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])->name('inquiries.status');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::redirect('/inquiry-view', '/users/inquiries')->name('inquiry-view');
    Route::redirect('/inquiry-reply', '/users/inquiries')->name('inquiry-reply');

    // Notifications
    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-all-read', [UserNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{notification}', [UserNotificationController::class, 'show'])->name('notifications.show');
    Route::delete('/notifications/{notification}', [UserNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::redirect('/notification-view', '/users/notifications')->name('notification-view');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/reviews', fn () => app(PageController::class)->userPage('reviews'))->name('reviews');
    Route::get('/review-view', fn () => app(PageController::class)->userPage('review-view'))->name('review-view');

    // Services & Products
    Route::get('/service-add', [ServiceController::class, 'create'])->name('service-add');
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::redirect('/service-edit', '/users/services')->name('service-edit');
    Route::redirect('/service-form', '/users/services')->name('service-form');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('project-add');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::patch('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.status');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Videos
    Route::get('/videos', [VideoController::class, 'index'])->name('videos');
    Route::get('/videos/create', [VideoController::class, 'create'])->name('video-form');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::patch('/videos/{video}/status', [VideoController::class, 'updateStatus'])->name('videos.status');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');

    // Offers
    Route::get('/offers', [OfferController::class, 'index'])->name('offers');
    Route::get('/offers/create', [OfferController::class, 'create'])->name('offer-form');
    Route::post('/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{offer}', [OfferController::class, 'update'])->name('offers.update');
    Route::patch('/offers/{offer}/status', [OfferController::class, 'updateStatus'])->name('offers.status');
    Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
    Route::post('/subscription/{plan}/order', [SubscriptionController::class, 'createOrder'])->name('subscription.order');
    Route::post('/subscription/payment/verify', [SubscriptionController::class, 'verify'])->name('subscription.verify');
    Route::post('/subscription/payment/failed', [SubscriptionController::class, 'failed'])->name('subscription.failed');
    Route::get('/payments', [PaymentHistoryController::class, 'index'])->name('payments');
    Route::get('/payments/{paymentLog}/invoice', [PaymentHistoryController::class, 'invoice'])->name('payments.invoice');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs');

    // Team
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/team-add', [TeamController::class, 'create'])->name('team-add');
    Route::post('/team', [TeamController::class, 'store'])->name('team.store');
    Route::get('/team/{team}/edit', [TeamController::class, 'edit'])->name('team.edit');
    Route::put('/team/{team}', [TeamController::class, 'update'])->name('team.update');
    Route::patch('/team/{team}/status', [TeamController::class, 'updateStatus'])->name('team.status');
    Route::delete('/team/{team}', [TeamController::class, 'destroy'])->name('team.destroy');
    Route::redirect('/team-edit', '/users/team')->name('team-edit');
    Route::redirect('/team-form', '/users/team')->name('team-form');
});

Route::redirect('/admin', '/admin/dashboard');

Route::redirect('/admin/login-legacy', '/admin/login');
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('login.submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
    Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
    Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
    Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
    Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
    Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/documents/{document}/approval', [CompanyProfileDocumentController::class, 'updateApproval'])->name('users.documents.approval');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/advertisements', [AdvertisementController::class, 'index'])->name('advertisements.index');
    Route::get('/advertisements/create', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::post('/advertisements', [AdvertisementController::class, 'store'])->name('advertisements.store');
    Route::get('/advertisements/{advertisement}/edit', [AdvertisementController::class, 'edit'])->name('advertisements.edit');
    Route::put('/advertisements/{advertisement}', [AdvertisementController::class, 'update'])->name('advertisements.update');
    Route::delete('/advertisements/{advertisement}', [AdvertisementController::class, 'destroy'])->name('advertisements.destroy');

    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');
});

Route::get('/logs', function () {
    $path = storage_path('logs/laravel.log');

    if (! file_exists($path)) {
        return response('Log file not found.', 404)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    return response(file_get_contents($path), 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('logs');

// Clear Logs
Route::get('/logs/clear', function () {
    $path = storage_path('logs/laravel.log');

    if (!File::exists($path)) {
        return response()->json([
            'status' => false,
            'message' => 'Log file not found.'
        ], 404);
    }

    File::put($path, '');

    return response()->json([
        'status' => true,
        'message' => 'Laravel log cleared successfully.'
    ]);
})->name('logs.clear');

Route::get('/clear-cache', function () {
    $commands = [
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear',
        'optimize:clear',
    ];

    $output = [];

    foreach ($commands as $command) {
        \Illuminate\Support\Facades\Artisan::call($command);
        $output[] = $command.': '.\Illuminate\Support\Facades\Artisan::output();
    }

    return response(implode("\n", $output), 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('clear-cache');

Route::get('/{slug}/qr.png', [PublicProfileController::class, 'qrImage'])
    ->name('front.profile.qr')
    ->where('slug', '[a-z0-9\-]+');
Route::get('/{slug}', [PublicProfileController::class, 'show'])
    ->name('front.profile.show')
    ->where('slug', '[a-z0-9\-]+');
