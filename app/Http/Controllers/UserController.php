<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function index()
    {
        return view('user.index');
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
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công..');
        }else {
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
        return view('profile', compact('user'));
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
        return view('change-password', compact('user', 'title'));
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
        return view('address', compact('addresses', 'title'));
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
}
