@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Manajemen Pengguna</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola akun administrator, guru BK, dan siswa yang terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/30 transition-all">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <!-- Notifikasi Sukses/Error -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-300 text-xs">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-600 dark:text-rose-300 text-xs">
        {{ session('error') }}
    </div>
    @endif

    <!-- Tabel Daftar User -->
    <div class="bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-soft dark:shadow-lg backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="py-3 px-4 rounded-l-lg">Nama</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role</th>
                        <th class="py-3 px-4">Dibuat Pada</th>
                        <th class="py-3 px-4 text-center rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/60">
                    @forelse($users as $user)
                    <tr>
                        <td class="py-3.5 px-4 font-medium text-slate-800 dark:text-white">{{ $user->name }}</td>
                        <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">{{ $user->email }}</td>
                        <td class="py-3.5 px-4">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-1 rounded-md bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-300 font-semibold text-[10px] uppercase border border-indigo-200 dark:border-indigo-500/30">Admin</span>
                            @elseif($user->role === 'counselor')
                                <span class="px-2.5 py-1 rounded-md bg-sky-50 dark:bg-sky-500/20 text-sky-600 dark:text-sky-300 font-semibold text-[10px] uppercase border border-sky-200 dark:border-sky-500/30">Counselor</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-300 font-semibold text-[10px] uppercase border border-emerald-200 dark:border-emerald-500/30">Student</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="py-3.5 px-4 text-center space-x-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

</div>
@endsection