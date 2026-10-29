@extends('layouts.app')

@section('title', 'Cài đặt website')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6 text-center">
        <img src="{{ asset(config('center.brand.logo_mark')) }}" alt="ICANDOIT" class="mx-auto mb-3 h-16 w-auto">
        <h1 class="text-2xl font-bold">Cài đặt website ICANDOIT</h1>
        <p class="text-sm text-slate-500">
            Điền thông tin bên dưới một lần duy nhất. Hệ thống sẽ tự tạo file cấu hình,
            tạo bảng dữ liệu và tài khoản quản trị.
        </p>
    </div>

    @unless($canWriteEnv)
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-100 px-4 py-3 text-sm text-amber-700">
            Thư mục gốc đang không cho ghi file. Hãy đặt quyền ghi (chmod 755) cho thư mục chứa website rồi tải lại trang.
        </div>
    @endunless

    <form method="POST" action="{{ route('install.store') }}" class="space-y-4">
        @csrf

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold">1. Cơ sở dữ liệu</h2>
            <p class="mb-4 text-sm text-slate-500">
                Lấy thông tin này trong Control Panel của hosting, mục <strong>MySQL Databases</strong>.
                Mật khẩu cơ sở dữ liệu thường chính là mật khẩu tài khoản hosting.
            </p>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="db_connection">Loại cơ sở dữ liệu</label>
                    <select id="db_connection" name="db_connection" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="mysql" @selected(old('db_connection', $defaults['db_connection']) === 'mysql')>MySQL (dùng cho hosting)</option>
                        <option value="sqlite" @selected(old('db_connection') === 'sqlite')>SQLite (lưu trong một file, dùng khi không có MySQL)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="db_host">Máy chủ cơ sở dữ liệu</label>
                    <input id="db_host" type="text" name="db_host" value="{{ old('db_host') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="sql123.infinityfree.com">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="db_port">Cổng</label>
                    <input id="db_port" type="number" name="db_port" value="{{ old('db_port', $defaults['db_port']) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="db_database">Tên cơ sở dữ liệu</label>
                    <input id="db_database" type="text" name="db_database" value="{{ old('db_database') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="if0_12345678_icandoit">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="db_username">Tên đăng nhập</label>
                    <input id="db_username" type="text" name="db_username" value="{{ old('db_username') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="if0_12345678">
                </div>

                <div class="sm:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="db_password">Mật khẩu cơ sở dữ liệu</label>
                    <input id="db_password" type="text" name="db_password" value="{{ old('db_password') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold">2. Tài khoản quản trị</h2>
            <p class="mb-4 text-sm text-slate-500">Tài khoản này dùng để đăng nhập và sửa nội dung website.</p>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="admin_name">Tên hiển thị</label>
                    <input id="admin_name" type="text" name="admin_name" value="{{ old('admin_name', 'Quản trị viên') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="admin_email">Email đăng nhập</label>
                    <input id="admin_email" type="email" name="admin_email" value="{{ old('admin_email') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="quantri@icandoit.ct.ws">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="admin_password">Mật khẩu</label>
                    <input id="admin_password" type="password" name="admin_password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="ít nhất 8 ký tự">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="admin_password_confirmation">Nhập lại mật khẩu</label>
                    <input id="admin_password_confirmation" type="password" name="admin_password_confirmation"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold">3. Thông tin website</h2>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="app_name">Tên website</label>
                    <input id="app_name" type="text" name="app_name" value="{{ old('app_name', $defaults['app_name']) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="app_url">Địa chỉ website</label>
                    <input id="app_url" type="text" name="app_url" value="{{ old('app_url', $defaults['app_url']) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <button class="rounded-lg bg-indigo-600 px-6 py-3 font-medium text-white hover:bg-indigo-700">
                Cài đặt website
            </button>
            <p class="mt-3 text-xs text-slate-500">
                Quá trình này mất khoảng 10–30 giây. Cài xong, trang này tự khoá lại để người khác không chạy lại được.
            </p>
        </div>
    </form>
</div>
@endsection
