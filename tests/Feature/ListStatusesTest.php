<?php

namespace Tests\Feature;

use App\Models\Status;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListStatusesTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */
    public function puedes_obtener_todos_los_estados()
    {
        $this->withoutExceptionHandling();

        $status1=factory(Status::class)->create(['created_at'=> now()->subDays(4)]);
        $status2=factory(Status::class)->create(['created_at'=> now()->subDays(3)]);
        $status3=factory(Status::class)->create(['created_at'=> now()->subDays(2)]);
        $status4=factory(Status::class)->create(['created_at'=> now()->subDays(1)]);

        $response=$this->getJson(route('statuses.index'));
        $response->assertSuccessful();
        $response->assertJson([
            'meta' => ['total'=> 4]
        ]);
        $response->assertJsonStructure([
            'data','links' => ['prev','next']
        ]);


        $this->assertEquals(
            $status4->body,
            $response->json('data.0.body')

        );
    }
    /**
     * @test
     */
    public function puedes_obtener_todos_los_estados_de_un_usuario_especifico()
    {
        $this->withoutExceptionHandling();

        $user=factory(User::class)->create();
        $status1=factory(Status::class)->create(['user_id'=>$user->id, 'created_at'=> now()->subDay()]);
        $status2=factory(Status::class)->create(['user_id'=>$user->id]);

        $otherStatuses= factory(status::class, 2)->create();

        $response=$this->actingAs($user)->getJson(route('users.statuses.index', $user));

        $response->assertSuccessful();
        $response->assertJson([
            'meta' => ['total'=> 2]
        ]);
        $response->assertJsonStructure([
            'data','links' => ['prev','next']
        ]);


        $this->assertEquals(
            $status2->body,
            $response->json('data.0.body')

        );
    }

    /**
     * @test
     */
    public function se_puede_ver_un_estado_individual(){
        $status=factory(Status::class)->create();

        $this->get($status->path())->assertSee($status->body);
    }
}
