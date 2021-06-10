<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanSeeProfilesTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function puedo_ver_los_perfiles()
    {

        $this->withoutExceptionHandling();
        factory(User::class)->create(['name'=> 'Kevin']);

        $this->get('@Kevin')->assertSee('Kevin');
    }
}
