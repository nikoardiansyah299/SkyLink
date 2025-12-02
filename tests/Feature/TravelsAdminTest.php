<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest is redirected to login when accessing create travel page', function () {
    $response = $this->get(route('travels.create'));
    $response->assertRedirect('/login');
});

test('client cannot access create travel page', function () {
    $user = User::factory()->create([ 'roles' => 'client' ]);

    $response = $this->actingAs($user)->get(route('travels.create'));
    $response->assertRedirect(route('travels.index'));
    $response->assertSessionHas('error');
});

test('admin can access create travel page', function () {
    $user = User::factory()->create([ 'roles' => 'admin' ]);
    $response = $this->actingAs($user)->get(route('travels.create'));

    $response->assertStatus(200);
    $response->assertSee('Tambah Data Travel');
});
