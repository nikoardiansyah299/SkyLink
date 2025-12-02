@extends('layout.master')

@section('content')
<div class="container mt-5" style="max-width: 650px;">

    <a href="{{ route('home') }}" class="text-decoration-none mb-3 d-inline-block">
        ← Kembali
    </a>

    <div class="p-4 rounded-4 shadow-sm bg-white">
        <h3 class="mb-4 fw-bold">Tambah Data Travel</h3>

        <form action="{{ route('travels.store') }}" method="POST">
            @csrf

            {{-- Maskapai --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Maskapai</label>
                <select name="maskapai_id" class="form-select rounded-3" required>
                    <option value="">-- Pilih Maskapai --</option>
                    @foreach ($maskapai as $m)
                        <option value="{{ $m->id }}">{{ $m->nama_maskapai }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Harga --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga</label>
                <input type="number" name="harga" class="form-control rounded-3" placeholder="Masukkan harga" required>
            </div>

            {{-- Kota Asal --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Kota Asal</label>
                <select name="asal_id" class="form-select rounded-3" required>
                    <option value="">-- Pilih Bandara Asal --</option>
                    @foreach ($bandara as $b)
                        <option value="{{ $b->id }}">
                            {{ $b->kota }} ({{ $b->kode_iata }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Kota Tujuan --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Kota Tujuan</label>
                <select name="tujuan_id" class="form-select rounded-3" required>
                    <option value="">-- Pilih Bandara Tujuan --</option>
                    @foreach ($bandara as $b)
                        <option value="{{ $b->id }}">
                            {{ $b->kota }} ({{ $b->kode_iata }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal Berangkat --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Berangkat</label>
                <input type="date" name="tanggal" class="form-control rounded-3" required>
            </div>

            {{-- Jam Berangkat --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Jam Berangkat</label>
                <input type="time" name="jam_berangkat" class="form-control rounded-3" required>
            </div>

            {{-- Jam Tiba --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Jam Tiba</label>
                <input type="time" name="jam_tiba" class="form-control rounded-3" required>
            </div>

            <button class="btn btn-primary rounded-3 px-4 py-2 w-100 fw-semibold">
                Simpan Data Penerbangan
            </button>
        </form>
    </div>

</div>
@endsection
