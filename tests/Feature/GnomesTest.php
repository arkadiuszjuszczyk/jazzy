<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GnomesTest extends TestCase
{
    private $testGnome = [
        'name' => 'Test Gnome',
        'age' => 30,
        'strength' => 56
    ];

    public static $testGnomeId;

    public function testGettingAllGnomes()
    {
        $response = $this->get('/api/gnomes');
        $response->assertStatus(200);
    }

    public function testStoringGnome()
    {
        $response = $this->post('/api/gnome/', $this->testGnome);
        $response->assertStatus(201);

        $response = $response->json();

        $this->assertArrayHasKey('id', $response);
        self::$testGnomeId = $response['id'];
    }

    public function testGettingOneGnome()
    {
        $gnomeId = self::$testGnomeId;

        if($gnomeId) {
            $response = $this->get('/api/gnome/' . $gnomeId);
            $response->assertStatus(200);

            $gnome = $response->json();

            foreach (array_keys($this->testGnome) as $key) {
                $this->assertArrayHasKey($key, $gnome);
            }
        }
    }

    public function testUpdatingGnome()
    {
        $gnomeId = self::$testGnomeId;

        if ($gnomeId) {
            $gnome = ['age' => 11, 'strength' => 11];
            $response = $this->put('/api/gnome/' . $gnomeId, $gnome);
            $response->assertStatus(200);

            $response = $this->get('/api/gnome/' . $gnomeId);
            $response->assertStatus(200);

            $gnome = $response->json();

            $this->assertEquals(11, $gnome['age']);
            $this->assertEquals(11, $gnome['strength']);
        }
    }

    public function testDeletingGnome()
    {
        $gnomeId = self::$testGnomeId;

        if ($gnomeId) {
            $response = $this->delete('/api/gnome/' . $gnomeId);
            $response->assertStatus(200);

            $response = $this->get('/api/gnome/' . $gnomeId);
            $response->assertStatus(404);
        }
    }
}
