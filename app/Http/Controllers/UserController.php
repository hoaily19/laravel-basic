<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use App\Models\Orders;
use App\Models\Orders_item;
use App\Models\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function index()
    {
        $title = 'Quản lý người dùng';
        $users = User::orderBy('created_at', 'asc')
            ->paginate(12);
        return view('admin.user.index', compact('title', 'users'));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:10',
        ],[
            'name.required' => 'Vui lòng nhập tên người dùng',
            'email.required' => 'Vui，lòng nhập điện thống',
            'email.email' => 'Vui，lòng nhập điện thống',
            'email.unique' => 'Email đã tốn tại',
            'password.required' => 'Vui，lòng nhập mật khẻu',
            'password.min' => 'Mật khẩu phải từ 6 số',
            'password.confirmed' => 'Mật khẩu không khớp', 
            'phone.max' => 'Số điện thoại tối đa 10 số',
            'phone.unique' => 'Số điện thoại phải 10 số',
            'phone.require' => 'Vui，nhập nhập số điện thoại'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please login.');
    }

    //Login
    public function LoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'email.required' => 'Vui, lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui，lòng nhập mật khẩu',
            'password.password' => 'Mật khẩu không khớp'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công..');
        } else {
            return back()->with('error', 'Sai tài khoản hoặc mật khẩu');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/login');
    }

    //login gooogle
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => null,
                    'oauth_provider' => 'google',
                    'oauth_id' => $googleUser->getId(),
                ]
            );

            Auth::login($user);
            return redirect()->intended('/');
        } catch (\Exception $e) {
            Log::error('Lỗi đăng nhập Google: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Đăng nhập Google thất bại.');
        }
    }


    //profile
    public function profile()
    {
        $user = Auth::user();
        return view('profile.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'avatar.image' => 'Ảnh đại diện phải là định dạng ảnh hợp lệ',
            'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB',
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $avatarName = time() . '.' . $request->avatar->getClientOriginalExtension();
            $avatarPath = 'avatars/' . $avatarName;
            $request->avatar->move(public_path('avatars'), $avatarName);

            $user->avatar = $avatarPath;
        }

        if ($user instanceof User) {
            if ($user instanceof User) {
                $user->save();
            } else {
                return back()->with('error', 'Không tìm thấy người dùng hợp lệ');
            }
        } else {
            return back()->with('error', 'Không tìm thấy người dùng hợp lệ');
        }

        return back()->with('success', 'Cập nhật hồ sơ thành công');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && File::exists(public_path($user->avatar))) {
            File::delete(public_path($user->avatar));
            $user->avatar = null;
            if ($user instanceof User) {
                $user->save();
            } else {
                return back()->with('error', 'Không tìm thấy người dùng hợp lệ');
            }
        }

        return response()->json(['success' => true, 'message' => 'Xóa ảnh thành công']);
    }

    //update new password
    public function changePassword()
    {
        $title = "Đổi mật khẩu";
        $user = Auth::user();
        return view('profile.change-password', compact('user', 'title'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng');
        }

        $user->password = Hash::make($request->password);

        if ($user instanceof User) {
            $user->save();
        } else {
            return back()->with('error', 'Không tìm thấy người dùng hợp lệ');
        }

        return back()->with('success', 'Đổi mật khẩu thành công');
    }

    //address
    public function address()
    {
        $title = "Thêm địa chỉ";
        $user = Auth::user();
        $addresses = $user->addresses ?? collect();
        Log::info('Addresses:', ['addresses' => $addresses]);
        return view('profile.address', compact('addresses', 'title', 'user'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'street' => 'required|string',
            'is_default' => 'nullable|boolean',
        ], [
            'receiver_name.required' => 'Vui lòng nhập tên người nhận',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự',
            'province.required' => 'Vui lòng chọn tỉnh/thành phố',
            'district.required' => 'Vui lòng chọn quận/huyện',
            'ward.required' => 'Vui lòng chọn xã/phường',
            'street.required' => 'Vui lòng nhập địa chỉ cụ thể',
        ]);

        $user = Auth::user();

        $address = new Address([
            'user_id' => $user->id,
            'receiver_name' => $request->receiver_name,
            'phone' => $request->phone,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'street' => $request->street,
            'is_default' => $request->is_default ? true : false,
        ]);

        if ($request->is_default) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address->save();

        return redirect()->route('profile.address')->with('success', 'Thêm địa chỉ thành công');
    }

    public function setAddress($id)
    {
        $address = Address::findOrFail($id);
        $user = Auth::user();
        if ($address->user_id !== $user->id) {
            return redirect()->route('profile.address')
                ->with('error', 'Địa chỉ không thuộc về bạn.');
        }
        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        return redirect()->route('profile.address')
            ->with('success', 'Đặt địa chỉ mặc định thành công.');
    }
    public function deleteAddress($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return redirect()->route('profile.address')->with('success', 'Xóa địa chỉ thành cong');
    }

    // forgot password
    public function forgotPassword(Request $request)
    {
        $title = 'Quên mật khẩu';
        return view('auth.forgot-password', compact('title'));
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Không tìm thấy tài khoản với email này'
        ]);

        $request->session()->forget(['email', 'otp_verified']);

        $user = User::where('email', $request->email)->first();

        $user->sendPasswordResetEmail();

        $request->session()->put('email', $request->email);

        return redirect()->route('password.verify-otp')
            ->with('success', 'Mã OTP đã được gửi đến email của bạn');
    }

    public function verifyOtp(Request $request)
    {
        $title = 'Xác nhận OTP';
        return view('auth.verify-otp', compact('title'));
    }

    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ], [
            'otp.required' => 'Vui lòng nhập mã OTP',
            'otp.numeric' => 'Mã OTP phải là số',
            'otp.digits' => 'Mã OTP phải có 6 chữ số'
        ]);

        $email = $request->session()->get('email');

        if (!$email) {
            return redirect()->route('password.forgot')
                ->with('error', 'Phiên làm việc đã hết hạn. Vui lòng thực hiện lại quy trình đặt lại mật khẩu.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.forgot')
                ->with('error', 'Không tìm thấy tài khoản');
        }

        if (!$user->verifyResetToken($request->otp)) {
            return redirect()->route('password.verify-otp')
                ->with('error', 'Mã OTP không hợp lệ hoặc đã hết hạn');
        }

        $request->session()->put('otp_verified', true);

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('password.forgot');
        }

        $title = 'Đặt lại mật khẩu';
        return view('auth.reset-password', compact('title'));
    }

    public function resetPassword(Request $request)
    {
        if (!session('otp_verified')) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        $email = $request->session()->get('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->clearResetToken();
            $user->save();

            $request->session()->forget(['email', 'otp_verified']);

            return redirect()->route('login')->with('success', 'Mật khẩu đã được đổi thành công. Vui lòng đăng nhập.');
        }

        return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
    }

    public function updateRole(Request $request, $id)
    {
        $currentUser = Auth::user();
        $targetUser = User::findOrFail($id);

        if ($currentUser->role === 'admin') {
            if ($targetUser->role === 'admin') {
                return back()->with('error', 'Không được thao tác với admin khác');
            }
            if (!in_array($request->role, ['user', 'admin'])) {
                return back()->with('error', 'Role không hợp lệ');
            }
        } else if ($currentUser->role === 'admin') {
            if ($targetUser->role === 'admin') {
                return back()->with('error', 'Không được thao tác với admin');
            }
            if (!in_array($request->role, ['user', 'admin'])) {
                return back()->with('error', 'Role không hợp lệ');
            }
        } else {
            return back()->with('error', 'Bạn không có quyền thực hiện thao tác này');
        }

        $targetUser->update(['role' => $request->role]);

        return back()->with('success', 'Cập nhật role thành công');
    }

    public function destroy($id)
    {
        $currentUser = Auth::user();
        $targetUser = User::findOrFail($id);


        if ($currentUser->role === 'admin') {
            if ($targetUser->id === $currentUser->id) {
                return back()->with('error', 'Không thể xóa chính mình');
            }
            if ($targetUser->role === 'admin') {
                return back()->with('error', 'Không thể xóa admin khác');
            }
        } else if ($currentUser->role === 'admin') {
            if ($targetUser->role !== 'user') {
                return back()->with('error', 'Bạn chỉ được xóa user thường');
            }
        } else {
            return back()->with('error', 'Bạn không có quyền thực hiện thao tác này');
        }

        $targetUser->delete();
        return back()->with('success', 'Xóa người dùng thành công');
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['orderItems.product', 'orderItems.variation'])
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('profile.orders', compact('orders'));
    }

  
    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = $user->orders()
            ->with(['orderItems.product', 'orderItems.variation.color', 'orderItems.variation.size', 'address'])
            ->findOrFail($id);

        return view('profile.order_detail', compact('order'));
    }



    public function cancelOrder($id)
    {
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (in_array($order->status, ['paid', 'shipping', 'delivering'])) {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('profile.orders')
            ->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    public function returnOrder($id)
    {
        $user = Auth::user();
        $oldOrder = $user->orders()->with('orderItems')->findOrFail($id);
        $defaultAddress = $user->addresses()->where('is_default', true)->first();
        if (!$defaultAddress) {
            return redirect()->route('profile.orders')
                ->with('error', 'Vui lòng thiết lập địa chỉ mặc định trước khi mua lại.');
        }
        $user->cart()->delete();

        foreach ($oldOrder->orderItems as $item) {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $item->product_id,
                'product_variations_id' => $item->product_variations_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        return redirect()->route('checkout.index')
            ->with('success', 'Sản phẩm đã được thêm vào giỏ hàng. Vui lòng kiểm tra và thanh toán.');
    }
}
