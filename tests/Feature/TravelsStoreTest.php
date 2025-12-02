php artisan migrate<?php

use App\Models\Bandara;
use App\Models\Maskapai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can store a new travel and it appears on index', function () {
    // create admin user
    $admin = User::factory()->create(['roles' => 'admin']);

    // create maskapai and bandara
    $m = Maskapai::create(['nama_maskapai' => 'TestAir', 'logo' => '/images/test.png']);
    $asal = Bandara::create(['nama_bandara' => 'Asal', 'kode_iata' => 'AAA', 'negara' => 'Indonesia', 'kota' => 'KotaA']);
    $tujuan = Bandara::create(['nama_bandara' => 'Tujuan', 'kode_iata' => 'BBB', 'negara' => 'Indonesia', 'kota' => 'KotaB']);

    $payload = [
        'maskapai_id' => $m->id,
        'harga' => 100000,
        'asal_id' => $asal->id,
        'tujuan_id' => $tujuan->id,
        'tanggal' => now()->addDays(1)->format('Y-m-d'),
        'jam_berangkat' => '09:00',
        'jam_tiba' => '11:00',
    ];

    $response = $this->actingAs($admin)->post(route('travels.store'), $payload);

    $response->assertRedirect(route('travels.index'));
    $this->assertDatabaseHas('penerbangan', ['harga' => 100000]);

    $page = $this->get(route('travels.index'));
    $page->assertStatus(200);
    $page->assertSee('TestAir');
});
