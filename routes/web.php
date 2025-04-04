<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrdersController;


//Admin
Route::middleware('check.role:admin')->group(function () {
    Route::get('/admin', function () {
        $title = 'Trang quản trị';
        return view('admin.index', compact('title'));
    });
   
    //Danh mục
    Route::prefix('admin/category')->name('admin.category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('delete');
    });
    //Thương hiệu
    Route::prefix('admin/brand')->name('admin.brand.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [BrandController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [BrandController::class, 'delete'])->name('delete');
    });
    //Sản Phẩm
    Route::prefix('admin/product')->name('admin.product.')->group(function () {
        Route::get('/', [ProductController::class, 'indexadmin'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete');
    });
    //Logo
    Route::prefix('admin/logo')->name('admin.logo.')->group(function () {
        Route::get('/', [LogoController::class, 'index'])->name('index');
        Route::get('/create', [LogoController::class, 'create'])->name('create');
        Route::post('/store', [LogoController::class, 'store'])->name('store');
        Route::get('/set-active/{id}', [LogoController::class, 'setActive'])->name('set-active');
        Route::get('/toggle-active/{id}', [LogoController::class, 'toggleActive'])->name('toggle-active');
        Route::get('/delete/{id}', [LogoController::class, 'delete'])->name('delete');
    });
    //color
    Route::prefix('admin/color')->name('admin.variants.color.')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('index');
        Route::get('/create', [ColorController::class, 'create'])->name('create');
        Route::post('/store', [ColorController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ColorController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ColorController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ColorController::class, 'delete'])->name('delete');
    });

    //Size
    Route::prefix('admin/size')->name('admin.variants.size.')->group(function () {
        Route::get('/', [SizeController::class, 'index'])->name('index');
        Route::get('/create', [SizeController::class, 'create'])->name('create');
        Route::post('/store', [SizeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SizeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [SizeController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SizeController::class, 'delete'])->name('delete');
    });
    //user
    Route::prefix('admin/user')->name('admin.user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/{id}/update-role', [UserController::class, 'updateRole'])->name('update-role');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/variants', [HomeController::class, 'variations'])->name('variants.index');
    });

    Route::prefix('admin/coupon')->name('admin.coupon.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/store', [CouponController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CouponController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CouponController::class, 'delete'])->name('delete');
    });
});


//User
Route::get('/', [HomeController::class, 'index'])->name('product.index');
Route::get('/product', [HomeController::class, 'product'])->name('product.product');
Route::get('/product/{slug}', [HomeController::class, 'show'])->name('product.show');

//profile
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/delete-avatar', [UserController::class, 'deleteAvatar'])->name('profile.delete.avatar');
Route::get('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.changePassword');
Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');

//address
Route::get('/profile/address', [UserController::class, 'address'])->name('profile.address');
Route::post('/profile/address/store', [UserController::class, 'storeAddress'])->name('profile.storeAddress');
Route::delete('/profile/address/delete/{id}', [UserController::class, 'deleteAddress'])->name('profile.deleteAddress');


// Route đăng ký
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'LoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

//google
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [UserController::class, 'handleGoogleCallback']);

// Route đăng xuất
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

//cart
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart/update/{id}/{change}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/update-quantity/{id}', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
    Route::get('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
    Route::post('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.delete.selected');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

//quen mat khau
Route::get('/quen-mat-khau', [UserController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/quen-mat-khau', [UserController::class, 'sendResetLink'])->name('password.send-link');
Route::get('/xac-nhan-otp', [UserController::class, 'verifyOtp'])->name('password.verify-otp');
Route::post('/xac-nhan-otp', [UserController::class, 'validateOtp'])->name('password.validate-otp');
Route::get('/dat-lai-mat-khau', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('/dat-lai-mat-khau', [UserController::class, 'resetPassword'])->name('password.update');

Route::middleware('is_login')->group(function () {
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [OrdersController::class, 'store'])->name('orders.store');
    Route::get('/checkout/success/{order}', [OrdersController::class, 'success'])->name('orders.success');
});

Route::get('/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('vnpay.callback');
Route::get('/momo/callback', [PaymentController::class, 'momoCallback'])->name('momo.callback');
Route::post('/momo/ipn', [PaymentController::class, 'momoIpn'])->name('momo.ipn');
Route::get('/zalopay/callback', [PaymentController::class, 'zalopayCallback'])->name('zalopay.callback');

//coupon
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('coupon.apply');

Route::get('/success', function () {
    $title = "Thành công!";
    return view('waring.success', compact('title'));
})->name('waring.success');
Route::get('/fail', function () {
    $title = "Thất bại!";
    return view('waring.fail', compact('title'));
})->name('waring.fail');


Route::get('/waring/success', [OrdersController::class, 'success'])->name('waring.success');
//404 Notfound
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});



