@extends('layout.master')
@section('title', 'Pesan Tiket')

@section('content')
<!-- <pre>{{ print_r($flight->maskapai, true) }}</pre> -->
<div class="container py-4">

    <h2 class="mb-3">Pesan Tiket Penerbangan</h2>

    {{-- Tambah Penumpang --}}
    <div class="mb-3 d-flex gap-2">
        <input type="number" id="jumlah-penumpang" class="form-control" 
            placeholder="Tambah Tiket" min="1" style="max-width: 200px">
        <button type="button" id="btn-tambah" class="btn btn-success">Tambah</button>
    </div>

    {{-- Info Penerbangan --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold">{{ optional($flight->maskapai)->nama_maskapai ?? $flight->nama_maskapai ?? 'Maskapai' }}</h5>
                <div>
                    {{ $flight->bandaraAsal->kota }} ({{ $flight->bandaraAsal->kode_iata }})
                    →
                    {{ $flight->bandaraTujuan->kota }} ({{ $flight->bandaraTujuan->kode_iata }})
                </div>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($flight->tanggal)->isoFormat('dddd, D MMM YYYY') }}
                    | {{ $flight->jam_berangkat }} - {{ $flight->jam_tiba }}
                </small>
            </div>
                <img src="{{ asset(ltrim(optional($flight->maskapai)->logo ?? $flight->gambar ?? 'images/default-logo.png', '/')) }}"
                    alt="Logo Maskapai"
                    class="img-fluid"
                    style="height: 60px;">

        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM PEMESANAN --}}
    <form action="{{ route('tiket.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_penerbangan" value="{{ $flight->id }}">

        <div id="penumpang-container">

            {{-- TEMPLATE PENUMPANG --}}
            <div class="card mb-3 p-3 shadow-sm penumpang-item">

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-3">Data Penumpang</h5>
                    <button type="button" class="btn btn-danger btn-sm d-none btn-hapus">
                        Hapus
                    </button>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nama Penumpang</label>
                        <input type="text" name="nama_penumpang[]" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik[]" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kelas Penerbangan</label>
                        <select name="kelas[]" class="form-select kelas-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="bisnis">Bisnis</option>
                            <option value="ekonomi">Ekonomi</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Seat</label>
                        <input type="text" name="seat[]" class="form-control seat-input" 
                               placeholder="Klik untuk pilih seat" readonly required>
                    </div>

                </div>

            </div>

        </div>

        <button class="btn btn-primary w-100">Konfirmasi Pemesanan</button>
    </form>
</div>

<!-- pilih seat -->
<div class="modal fade" id="seatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Seat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="seat-grid" class="d-flex flex-wrap gap-2"></div>
            </div>

        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
<script>
let activeInput = null;  
let activeClass = null;

const takenSeats = @json($takenSeats);

// Saat klik seat input → buka modal
document.addEventListener("click", function(e){
    if(e.target.classList.contains("seat-input")) {

        const parent = e.target.closest(".penumpang-item");
        const kelasSelect = parent.querySelector(".kelas-select");

        if (!kelasSelect.value) {
            alert("Pilih kelas penerbangan dulu!");
            return;
        }

        activeClass = kelasSelect.value;
        activeInput = e.target;

        generateSeatGrid(activeClass);

        new bootstrap.Modal(document.getElementById('seatModal')).show();
    }
});

// Generate seat grid sesuai kelas
function generateSeatGrid(kelas) {
    const container = document.getElementById("seat-grid");
    container.innerHTML = "";

    let startRow, endRow, cols;

    if (kelas === "bisnis") {
        startRow = 1; endRow = 7;
        cols = ['A','B','C','D'];
    } else {
        startRow = 8; endRow = 20;
        cols = ['A','B','C','D','E'];
    }

    for (let i = startRow; i <= endRow; i++) {
        cols.forEach(r => {
            const seat = i + r;
            const disabled = takenSeats.includes(seat);

            const btn = document.createElement("button");
            btn.textContent = seat;
            btn.className = "btn btn-sm seat-box " + 
                            (disabled ? "btn-secondary" : "btn-outline-primary");
            btn.style.minWidth = "55px";

            if (!disabled) {
                btn.addEventListener("click", () => {
                    activeInput.value = seat;
                    bootstrap.Modal.getInstance(document.getElementById('seatModal')).hide();
                });
            } else {
                btn.disabled = true;
            }

            container.appendChild(btn);
        });

        container.appendChild(document.createElement("div")).style.width = "100%";
    }
}


// Tambah penumpang
const container = document.getElementById('penumpang-container');
document.getElementById('btn-tambah').addEventListener('click', () => {
    let item = document.querySelector('.penumpang-item').cloneNode(true);

    item.querySelectorAll("input").forEach(i => i.value = "");
    item.querySelectorAll("select").forEach(s => s.selectedIndex = 0);

    item.querySelector(".btn-hapus").classList.remove("d-none");
    item.querySelector(".btn-hapus").addEventListener("click", () => item.remove());

    container.appendChild(item);
});
</script>


<style>
.seat-box {
    padding: 8px;
    border-radius: 8px;
    font-size: 14px;
}
</style>

@endsection
