<?php

use Database\Seeders\DatabaseSeeder;

test('the application returns a successful response', function () {
    $this->seed(DatabaseSeeder::class);
    $response = $this->get('/');

    $response->assertStatus(200);
});
