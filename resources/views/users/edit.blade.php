@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-3xl space-y-6">
    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-xs">
            <p class="font-bold mb-2">Periksa kembali input pengguna:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800">Perbarui Data Pengguna</h3>
                <p class="text-xs text-slate-500">Ubah nama, role, akun login, dan wilayah kecamatan pengguna.</p>
            </div>
            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            @method('PUT')

            <div class="md:col-span-2">
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Role</label>
                <select id="role" name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500" data-role-select>
                    @foreach($roleOptions as $roleValue => $roleLabel)
                        <option value="{{ $roleValue }}" @selected(old('role', $user->role) === $roleValue)>{{ $roleLabel }}</option>
                    @endforeach
                </select>
            </div>

            <div data-kecamatan-wrapper>
                <label for="kecamatan_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Wilayah Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500" data-kecamatan-select>
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatan as $item)
                        <option value="{{ $item->id }}" @selected((string) old('kecamatan_id', $user->kecamatan_id) === (string) $item->id)>{{ $item->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Password Baru</label>
                <input id="password" type="password" name="password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
                <p class="mt-1 text-[11px] text-slate-400">Kosongkan jika password tidak diubah.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Konfirmasi Password Baru</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
            </div>

            <div class="md:col-span-2 flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-sm">
                    Perbarui Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roleSelect = document.querySelector('[data-role-select]');
        const kecamatanWrapper = document.querySelector('[data-kecamatan-wrapper]');
        const kecamatanSelect = document.querySelector('[data-kecamatan-select]');

        if (!roleSelect || !kecamatanWrapper || !kecamatanSelect) {
            return;
        }

        const toggleKecamatanField = () => {
            const requiresKecamatan = roleSelect.value === '{{ \App\Models\User::ROLE_KECAMATAN }}';
            kecamatanWrapper.classList.toggle('hidden', !requiresKecamatan);
            kecamatanSelect.required = requiresKecamatan;

            if (!requiresKecamatan) {
                kecamatanSelect.value = '';
            }
        };

        toggleKecamatanField();
        roleSelect.addEventListener('change', toggleKecamatanField);
    });
</script>
@endsection
