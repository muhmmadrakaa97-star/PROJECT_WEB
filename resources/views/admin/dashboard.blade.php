@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-2xl font-bold text-unsriNavy">
                Dashboard Admin & Pengelola
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Kelola dan verifikasi laporan pengaduan sivitas akademika Universitas Sriwijaya.
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 shadow-sm">
            <div class="flex items-center text-sm text-gray-500">
                <i class="fa-solid fa-calendar-days mr-2 text-unsriTeal"></i>
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

        {{-- TOTAL --}}
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-unsriNavy p-5">
            <p class="text-sm font-semibold text-gray-500 uppercase">
                Total Laporan
            </p>

            <p class="text-3xl font-bold text-unsriNavy mt-2">
                {{ $total }}
            </p>
        </div>


        {{-- MENUNGGU VERIFIKASI --}}
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-orange-500 p-5">
            <p class="text-sm font-semibold text-gray-500 uppercase">
                Menunggu Verifikasi
            </p>

            <p class="text-3xl font-bold text-orange-500 mt-2">
                {{ $baru }}
            </p>
        </div>


        {{-- SEDANG DIPROSES --}}
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-5">
            <p class="text-sm font-semibold text-gray-500 uppercase">
                Sedang Diproses
            </p>

            <p class="text-3xl font-bold text-blue-500 mt-2">
                {{ $proses }}
            </p>
        </div>


        {{-- SELESAI --}}
        <div class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-5">
            <p class="text-sm font-semibold text-gray-500 uppercase">
                Selesai
            </p>

            <p class="text-3xl font-bold text-emerald-500 mt-2">
                {{ $selesai }}
            </p>
        </div>

    </div>


    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">

        <form
            action="{{ route('admin.dashboard') }}"
            method="GET"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

            {{-- SEARCH --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">
                    Cari Tiket / Judul
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Ketik kata kunci..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-unsriTeal focus:border-unsriTeal">
            </div>


            {{-- KATEGORI --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">
                    Kategori
                </label>

                <select
                    name="kategori"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-unsriTeal focus:border-unsriTeal">

                    <option value="">
                        Semua Kategori
                    </option>

                    <option
                        value="Sarana dan Prasarana"
                        @selected(request('kategori')=='Sarana dan Prasarana' )>
                        Sarana dan Prasarana
                    </option>

                    <option
                        value="Akademik"
                        @selected(request('kategori')=='Akademik' )>
                        Akademik
                    </option>

                    <option
                        value="Keuangan"
                        @selected(request('kategori')=='Keuangan' )>
                        Keuangan
                    </option>

                    <option
                        value="Kemahasiswaan"
                        @selected(request('kategori')=='Kemahasiswaan' )>
                        Kemahasiswaan
                    </option>

                </select>
            </div>


            {{-- STATUS --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-unsriTeal focus:border-unsriTeal">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="Menunggu Verifikasi"
                        @selected(request('status')=='Menunggu Verifikasi' )>
                        Menunggu Verifikasi
                    </option>

                    <option
                        value="Diterima"
                        @selected(request('status')=='Diterima' )>
                        Diterima
                    </option>

                    <option
                        value="Sedang Diproses"
                        @selected(request('status')=='Sedang Diproses' )>
                        Sedang Diproses
                    </option>

                    <option
                        value="Menunggu Informasi Tambahan"
                        @selected(request('status')=='Menunggu Informasi Tambahan' )>
                        Menunggu Informasi Tambahan
                    </option>

                    <option
                        value="Selesai"
                        @selected(request('status')=='Selesai' )>
                        Selesai
                    </option>

                    <option
                        value="Ditolak"
                        @selected(request('status')=='Ditolak' )>
                        Ditolak
                    </option>

                </select>
            </div>


            {{-- BUTTON FILTER --}}
            <div>
                <button
                    type="submit"
                    class="w-full bg-unsriNavy hover:bg-blue-900 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition">

                    <i class="fa-solid fa-filter mr-1"></i>
                    Terapkan Filter

                </button>
            </div>

        </form>

    </div>


    {{-- TABEL PENGADUAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- TABLE HEADER --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

            <div>
                <h2 class="text-xl font-bold text-unsriNavy">
                    Manajemen Laporan Pengaduan
                </h2>
            </div>

            <div class="text-sm text-gray-500">
                Menampilkan {{ $pengaduans->total() }} data
            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="text-left px-5 py-4 font-bold text-gray-600 uppercase text-xs">
                            Tiket & Pelapor
                        </th>

                        <th class="text-left px-5 py-4 font-bold text-gray-600 uppercase text-xs">
                            Judul & Kategori
                        </th>

                        <th class="text-left px-5 py-4 font-bold text-gray-600 uppercase text-xs">
                            Lokasi & Urgensi
                        </th>

                        <th class="text-left px-5 py-4 font-bold text-gray-600 uppercase text-xs">
                            Status & Unit
                        </th>

                        <th class="text-left px-5 py-4 font-bold text-gray-600 uppercase text-xs">
                            Aksi / Ubah Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($pengaduans as $item)

                    <tr class="hover:bg-gray-50 transition">

                        {{-- TIKET & PELAPOR --}}
                        <td class="px-5 py-5 align-top">

                            <div class="font-bold text-unsriNavy">
                                {{ $item->nomor_tiket }}
                            </div>

                            <div class="text-xs text-gray-500 mt-1">

                                <i class="fa-solid fa-user mr-1"></i>

                                @if($item->is_anonim)
                                Anonim
                                <span class="block">
                                    (Dirahasiakan)
                                </span>
                                @else
                                {{ $item->user->name ?? 'Pengguna' }}
                                @endif

                            </div>

                            <div class="text-xs text-gray-400 mt-1">
                                {{ $item->created_at->format('d M Y H:i') }}
                            </div>

                        </td>


                        {{-- JUDUL & KATEGORI --}}
                        <td class="px-5 py-5 align-top">

                            <div class="font-bold text-gray-800 text-base">
                                {{ $item->judul }}
                            </div>

                            <div class="text-xs text-unsriTeal font-semibold mt-1">
                                {{ $item->kategori }}
                            </div>

                            <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 mt-2 text-xs text-gray-600">

                                {{ $item->deskripsi }}

                            </div>

                            @if($item->bukti)

                            <div class="mt-2">

                                <a
                                    href="{{ asset('storage/' . $item->bukti) }}"
                                    target="_blank"
                                    class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium">

                                    <i class="fa-solid fa-paperclip mr-1"></i>
                                    Lihat Bukti

                                </a>

                            </div>

                            @endif

                        </td>


                        {{-- LOKASI & URGENSI --}}
                        <td class="px-5 py-5 align-top">

                            <div class="text-gray-700">

                                <i class="fa-solid fa-location-dot text-unsriTeal mr-1"></i>

                                {{ $item->lokasi }}

                            </div>

                            <div class="inline-block mt-2 bg-gray-100 rounded-md px-2 py-1 text-xs font-semibold text-gray-700">

                                Urgensi:
                                {{ $item->urgensi }}

                            </div>

                        </td>


                        {{-- STATUS & UNIT --}}
                        <td class="px-5 py-5 align-top">

                            @php

                            $statusClass = match($item->status) {

                            'Selesai' =>
                            'bg-emerald-100 text-emerald-700',

                            'Ditolak' =>
                            'bg-rose-100 text-rose-700',

                            'Sedang Diproses' =>
                            'bg-blue-100 text-blue-700',

                            'Diterima' =>
                            'bg-cyan-100 text-cyan-700',

                            'Menunggu Informasi Tambahan' =>
                            'bg-yellow-100 text-yellow-700',

                            default =>
                            'bg-blue-100 text-blue-700',

                            };

                            @endphp


                            <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClass }}">

                                {{ $item->status }}

                            </span>


                            @if($item->unit_terkait)

                            <div class="mt-3 text-xs text-gray-500">

                                <span class="font-semibold text-gray-700">
                                    Unit:
                                </span>

                                {{ $item->unit_terkait }}

                            </div>

                            @endif


                            @if($item->status == 'Ditolak' && $item->alasan_ditolak)

                            <div class="mt-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg p-3 text-xs">

                                <strong>
                                    Alasan Ditolak:
                                </strong>

                                <div class="mt-1">
                                    {{ $item->alasan_ditolak }}
                                </div>

                            </div>

                            @endif

                        </td>


                        {{-- AKSI / UBAH STATUS --}}
                        <td class="px-5 py-5 align-top">

                            {{-- FORM UBAH STATUS --}}
                            <form
                                action="{{ route('admin.pengaduan.status', $item->id) }}"
                                method="POST"
                                class="bg-gray-50 border border-gray-200 rounded-xl p-3 min-w-[210px]">

                                @csrf
                                @method('PUT')


                                {{-- STATUS --}}
                                <div class="mb-3">

                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Ubah Status:
                                    </label>

                                    <select
                                        name="status"
                                        class="w-full border border-gray-300 rounded-lg px-2.5 py-2 text-sm focus:ring-unsriTeal focus:border-unsriTeal">

                                        <option
                                            value="Menunggu Verifikasi"
                                            @selected($item->status == 'Menunggu Verifikasi')>
                                            Menunggu Verifikasi
                                        </option>

                                        <option
                                            value="Diterima"
                                            @selected($item->status == 'Diterima')>
                                            Diterima
                                        </option>

                                        <option
                                            value="Sedang Diproses"
                                            @selected($item->status == 'Sedang Diproses')>
                                            Sedang Diproses
                                        </option>

                                        <option
                                            value="Menunggu Informasi Tambahan"
                                            @selected($item->status == 'Menunggu Informasi Tambahan')>
                                            Menunggu Informasi Tambahan
                                        </option>

                                        <option
                                            value="Selesai"
                                            @selected($item->status == 'Selesai')>
                                            Selesai
                                        </option>

                                        <option
                                            value="Ditolak"
                                            @selected($item->status == 'Ditolak')>
                                            Ditolak
                                        </option>

                                    </select>

                                </div>


                                {{-- UNIT PENANGANAN --}}
                                <div class="mb-3">

                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Unit Penanganan:
                                    </label>

                                    <input
                                        type="text"
                                        name="unit_terkait"
                                        value="{{ $item->unit_terkait }}"
                                        placeholder="Contoh: ICT Center / Perlengkapan"
                                        class="w-full border border-gray-300 rounded-lg px-2.5 py-2 text-xs focus:ring-unsriTeal focus:border-unsriTeal">

                                </div>


                                {{-- ALASAN DITOLAK --}}
                                <div class="mb-3">

                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Alasan Ditolak:
                                    </label>

                                    <textarea
                                        name="alasan_ditolak"
                                        rows="2"
                                        placeholder="Isi jika ditolak..."
                                        class="w-full border border-gray-300 rounded-lg px-2.5 py-2 text-xs focus:ring-unsriTeal focus:border-unsriTeal">{{ $item->alasan_ditolak }}</textarea>

                                </div>


                                {{-- SIMPAN --}}
                                <button
                                    type="submit"
                                    class="w-full bg-unsriNavy hover:bg-blue-900 text-white font-semibold py-2.5 px-3 rounded-lg text-xs transition shadow-sm">

                                    <i class="fa-solid fa-floppy-disk mr-1"></i>

                                    Simpan Perubahan

                                </button>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-5 py-12 text-center">

                            <div class="text-gray-400">

                                <i class="fa-solid fa-inbox text-4xl mb-3"></i>

                                <p class="font-semibold">
                                    Belum ada laporan pengaduan.
                                </p>

                                <p class="text-xs mt-1">
                                    Data pengaduan akan muncul di sini.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($pengaduans->hasPages())

        <div class="px-5 py-4 border-t border-gray-100">

            {{ $pengaduans->links() }}

        </div>

        @endif

    </div>

</div>

@endsection