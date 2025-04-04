@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <script>
            iziToast.success({
                title: 'Thành công',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif

    <!-- Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-6">
        <div>
            <h3 class="tw-text-2xl tw-font-bold">Quản lý người dùng</h3>
            <p class="tw-text-gray-500 tw-mt-1">Danh sách người dùng hệ thống</p>
        </div>
    </div>

    <!-- Table -->
    <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-overflow-hidden">
        <table class="table table-bordered align-middle mb-0">
            <thead class="tw-bg-gray-100">
                <tr>
                    <th class="tw-py-3 tw-px-4">#</th>
                    <th class="tw-py-3 tw-px-4">Tên</th>
                    <th class="tw-py-3 tw-px-4">Email</th>
                    <th class="tw-py-3 tw-px-4">Họ tên</th>
                    <th class="tw-py-3 tw-px-4">Avatar</th>
                    <th class="tw-py-3 tw-px-4">Role</th>
                    <th class="tw-py-3 tw-px-4 tw-text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="tw-px-4">{{ $loop->iteration }}</td>
                        <td class="tw-px-4">{{ $user->username ?? 'N/A' }}</td>
                        <td class="tw-px-4">{{ $user->email ?? 'N/A' }}</td>
                        <td class="tw-px-4">{{ $user->fullname ?? 'N/A' }}</td>
                        <td class="tw-px-4">
                            @if($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="Avatar" class="tw-w-10 tw-h-10 tw-rounded-full tw-object-cover">
                            @else
                                <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-gray-200 tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-user tw-text-gray-500"></i>
                                </div>
                            @endif
                        </td>
                        <td class="tw-px-4">
                            @switch($user->role)
                                @case('user')
                                    <span class="tw-bg-purple-100 tw-text-purple-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">user</span>
                                    @break
                                @case('admin')
                                    <span class="tw-bg-blue-100 tw-text-blue-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">Admin</span>
                                    @break
                                @default
                                    <span class="tw-bg-green-100 tw-text-green-800 tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium">User</span>
                            @endswitch
                        </td>
                        <td class="tw-px-4 tw-text-center tw-space-x-1">
                            @if(Auth::user()->id !== $user->id)
                                @if(Auth::user()->role == 'user' && $user->role != 'user')
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                            id="roleDropdown{{ $user->id }}" data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                            <i class="fas fa-user-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="roleDropdown{{ $user->id }}">
                                            <li>
                                                <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" name="role" value="admin" 
                                                        class="dropdown-item {{ $user->role == 'admin' ? 'active' : '' }}">
                                                        <i class="fas fa-user-shield me-2"></i> Chuyển Admin
                                                    </button>
                                                    <button type="submit" name="role" value="user" 
                                                        class="dropdown-item {{ $user->role == 'user' ? 'active' : '' }}">
                                                        <i class="fas fa-user me-2"></i> Chuyển User
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                
                                @elseif(Auth::user()->role == 'admin' && $user->role != 'user')
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                                            id="roleDropdown{{ $user->id }}" data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                            <i class="fas fa-user-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="roleDropdown{{ $user->id }}">
                                            <li>
                                                <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" name="role" value="admin" 
                                                        class="dropdown-item {{ $user->role == 'admin' ? 'active' : '' }}">
                                                        <i class="fas fa-user-shield me-2"></i> Chuyển Admin
                                                    </button>
                                                    <button type="submit" name="role" value="user" 
                                                        class="dropdown-item {{ $user->role == 'user' ? 'active' : '' }}">
                                                        <i class="fas fa-user me-2"></i> Chuyển User
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif

                                @if(($user->role == 'user' && Auth::user()->role == 'admin') || 
                                    (Auth::user()->role == 'user' && $user->role != 'user'))
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                        title="Không đủ quyền hạn">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif
                            @else
                                <span class="tw-text-sm tw-text-gray-500">Tài khoản của bạn</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Không có người dùng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="tw-flex tw-justify-between tw-items-center tw-mt-4">
        <div class="tw-text-sm tw-text-gray-600">
            Hiển thị {{ $users->firstItem() }} đến {{ $users->lastItem() }} trong tổng số {{ $users->total() }} người dùng
        </div>
        <div>
            {{ $users->links() }}
        </div>
    </div>
    @endif
@endsection