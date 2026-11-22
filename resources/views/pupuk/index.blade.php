@extends('layouts.app')

@section('title', 'Manajemen Pupuk - HaloTani')

@php
    // TODO: Ubah ke false jika fitur Manajemen Pupuk sudah siap dibuka untuk petani
    $sedangDikembangkan = true;
@endphp

@push('styles')
<style>
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; }
    th { background-color: #2e7d32; color: white; font-size: 0.875rem; }
    td { font-size: 0.875rem; }
    .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; font-size: 0.875rem; }
    .btn-green { background: #2e7d32; color: white; }
    .btn-blue { background: #0288d1; color: white; }
    .btn-red { background: #d32f2f; color: white; }
    .btn-gray { background: #6b7280; color: white; }
    .alert-success { background: #e8f5e9; color: #2e7d32; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .badge-warning { background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    .badge-safe { background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; }

    /* Status Dalam Pengembangan */
    .dev-card {
        max-width: 44rem;
        padding: clamp(1.5rem, 4vw, 2.5rem);
    }
    .dev-stack {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 1.25rem;
    }
    .dev-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        background: #e8f5e9;
        color: #1b5e20;
        flex-shrink: 0;
    }
    .dev-heading {
        font-size: 1.375rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.35;
        margin-bottom: 0.5rem;
    }
    .dev-desc {
        font-size: 0.9375rem;
        color: #4b5563;
        line-height: 1.65;
        max-width: 38rem;
    }
    .dev-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 0.25rem;
    }
    .dev-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 0.625rem 1.125rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }
    .dev-btn-primary {
        background: #2e7d32;
        color: #ffffff;
        border: 1px solid #2e7d32;
    }
    .dev-btn-primary:hover {
        background: #1b5e20;
        border-color: #1b5e20;
    }
    .dev-btn-secondary {
        background: #f9fafb;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    .dev-btn-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }
    .dev-btn:focus-visible {
        outline: 2px solid #1b5e20;
        outline-offset: 2px;
    }
    @media (prefers-reduced-motion: reduce) {
        .dev-btn { transition: none; }
    }
</style>
@endpush

@section('content')
@if ($sedangDikembangkan)
    <section class="card dev-card" aria-labelledby="pupuk-dev-heading">
        <div class="dev-stack">
            <div class="dev-icon" aria-hidden="true">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="24" height="24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                </svg>
            </div>

            <div>
                <h1 id="pupuk-dev-heading" class="dev-heading">Manajemen Pupuk Masih dalam Pengembangan</h1>
                <p class="dev-desc">
                    Tim HaloTani sedang menyempurnakan pencatatan stok pupuk, pencarian inventaris, dan laporan PDF agar dapat digunakan dengan stabil. Sementara waktu, kamu tetap dapat mengirim keluhan tanaman atau melihat riwayat analisis.
                </p>
            </div>

            <div class="dev-actions">
                <a href="{{ route('filament.petani.pages.kirim-keluhan') }}" class="dev-btn dev-btn-primary">
                    Kirim Keluhan Tanaman
                </a>
                <a href="{{ route('filament.petani.pages.dashboard') }}" class="dev-btn dev-btn-secondary">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
@else
<div class="card">
    <h2 style="margin-bottom: 15px;">Daftar Inventaris Pupuk</h2>

    {{-- Pesan Sukses Flash --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div style="display: flex; gap: 8px; margin-bottom: 15px; flex-wrap: wrap;">
        <a href="{{ route('pupuk.create') }}" class="btn btn-green">+ Tambah Pupuk</a>
        <a href="{{ route('pupuk.export.pdf') }}" class="btn btn-red" target="_blank">📄 Unduh Laporan PDF</a>
        <a href="{{ route('pupuk.export.pdf', ['stream' => 1]) }}" class="btn btn-blue" target="_blank">👁️ Cetak / Preview PDF</a>
        <a href="{{ route('filament.petani.pages.dashboard') }}" class="btn btn-gray">← Kembali ke Dashboard</a>
    </div>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('pupuk.index') }}" style="margin-bottom: 15px; display: flex; gap: 6px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pupuk..." style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" class="btn btn-blue">Cari</button>
        <a href="{{ route('pupuk.index') }}" class="btn btn-gray">Reset</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pupuk</th>
                <th>Jenis</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Status Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pupuks as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>
                        @if($item->stok <= 5)
                            <span class="badge-warning">Hampir Habis</span>
                        @else
                            <span class="badge-safe">Tersedia</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pupuk.edit', $item->id) }}" class="btn btn-blue">Edit</a>
                        <form action="{{ route('pupuk.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada data pupuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
@endsection