@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium shadow-xs">
            {{ session('success') }}
        </div>
    @endif

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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Pengguna</h3>
                        <p class="text-xs text-slate-500">Kelola akun berdasarkan nama, role, dan wilayah kecamatan.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-xs font-bold text-blue-700">
                        Total: {{ $users->total() }} Pengguna
                    </span>
                </div>

                <form method="GET" action="{{ route('users.index') }}" class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-2 text-xs">
                    <div class="md:col-span-5">
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama, username, atau email..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-800 outline-none focus:border-blue-500 shadow-2xs font-medium">
                    </div>
                    <div class="md:col-span-3">
                        <select name="role" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 font-semibold outline-none focus:border-blue-500 shadow-2xs">
                            <option value="">Semua Role</option>
                            @foreach($roleOptions as $roleValue => $roleLabel)
                                <option value="{{ $roleValue }}" @selected(($filters['role'] ?? '') === $roleValue)>{{ $roleLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <select name="kecamatan_id" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-700 font-semibold outline-none focus:border-blue-500 shadow-2xs">
                            <option value="">Semua Wilayah</option>
                            @foreach($kecamatan as $item)
                                <option value="{{ $item->id }}" @selected((string) ($filters['kecamatan_id'] ?? '') === (string) $item->id)>{{ $item->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1 flex items-center gap-1.5">
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-2xs transition">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3 text-left">Nama</th>
                            <th class="px-5 py-3 text-left">Akun</th>
                            <th class="px-5 py-3 text-left">Role</th>
                            <th class="px-5 py-3 text-left">Wilayah</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                                </td>
                                <td class="px-5 py-4 align-top text-slate-600">
                                    <p class="font-semibold">{{ $user->username }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->email ?: '-' }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold">
                                        {{ $user->role_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top text-slate-600">
                                    {{ $user->kecamatan?->nama_kecamatan ?? '-' }}
                                </td>
                                <td class="px-5 py-4 align-top text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="px-3 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-xs font-bold transition" @disabled(auth()->id() === $user->id)>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                    Belum ada pengguna yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-800">Tambah Pengguna</h3>
                <p class="text-xs text-slate-500">Role admin dan pejabat OPD/instansi tidak perlu memilih wilayah. User wilayah wajib memilih kecamatan.</p>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-4" data-user-form>
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Role</label>
                    <select id="role" name="role" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500" data-role-select>
                        @foreach($roleOptions as $roleValue => $roleLabel)
                            <option value="{{ $roleValue }}" @selected(old('role', \App\Models\User::ROLE_ADMIN) === $roleValue)>{{ $roleLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div data-kecamatan-wrapper>
                    <label for="kecamatan_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Wilayah Kecamatan</label>
                    <select id="kecamatan_id" name="kecamatan_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500" data-kecamatan-select>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatan as $item)
                            <option value="{{ $item->id }}" @selected((string) old('kecamatan_id') === (string) $item->id)>{{ $item->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Password</label>
                    <input id="password" type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500">
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-sm">
                    Simpan Pengguna
                </button>
            </form>
        </div>
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
