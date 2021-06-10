<?php

namespace Tests\Browser;

use App\Models\Status;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanSeeAllStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     * @throws \Throwable
     */
    public function los_usuarios_pueden_ver_todos_los_estados_en_el_inicio()
    {
        $statuses=factory(Status::class, 3)->create(['created_at' => now()->subMinute()]);

        $this->browse(function (Browser $browser) use ($statuses){
            $browser->visit('/')
                ->waitForText($statuses->first()->body);

            foreach ($statuses as $status){

                $browser->assertSee($status->body)
                    ->assertSee($status->user->name)
                    ->assertSee($status->created_at->diffForHumans())
                ;
            }
        });
    }
}
