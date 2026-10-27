<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\SiteContent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'content' => SiteContent::current(),
        'pages' => Page::query()->where('status', 'published')->orderBy('title')->get(),
    ]);
})->name('home');

Route::get('/storage/heading-backgrounds/{filename}', [PageController::class, 'showHeadingBackground'])
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('pages.heading-background');
Route::get('/storage/page-images/{filename}', [PageController::class, 'showPageImage'])
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('pages.image');
Route::get('/storage/page-videos/{filename}', [PageController::class, 'showPageVideo'])
    ->where('filename', '[A-Za-z0-9._-]+')
    ->name('pages.video');

Route::get('/halaman/{slug}', function (string $slug) {
    $page = Page::query()
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    return view('pages.show', [
        'page' => $page,
        'siteContent' => SiteContent::current(),
        'navigationMenus' => NavigationMenu::query()->with(['items.page'])->orderBy('name')->get(),
    ]);
})->name('pages.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('/content', [ContentController::class, 'update'])->name('content.update');
        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::post('/menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
        Route::post('/menus/{menu}/items/reorder', [MenuController::class, 'reorderItems'])->name('menus.items.reorder');
        Route::put('/menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('/menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::get('/pages/{page}/veditor', [PageController::class, 'visualEditor'])->name('pages.visual-editor');
        Route::post('/pages/{page}/veditor/background-image', [PageController::class, 'uploadHeadingBackground'])->name('pages.visual-editor.background-image');
        Route::post('/pages/{page}/veditor/image', [PageController::class, 'uploadPageImage'])->name('pages.visual-editor.image');
        Route::post('/pages/{page}/veditor/video', [PageController::class, 'uploadPageVideo'])->name('pages.visual-editor.video');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::put('/pages/{page}/veditor', [PageController::class, 'updateVisualEditor'])->name('pages.visual-editor.update');
        Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
