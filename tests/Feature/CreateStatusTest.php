<?php

namespace Tests\Feature;

use App\Http\Resources\StatusResource;
use App\Models\status;
use App\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use App\Events\StatusCreated;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function los_invitados_no_pueden_crear_estados(){

        $response=$this->postJson(route('statuses.store'),['body'=> 'Mi primer estado']);

        $response->assertStatus(401);
    }

    /** @test */
    public function un_usuario_autenticado_puede_crear_estados()
    {
        Event::fake(StatusCreated::class);

        $this->withoutExceptionHandling();

        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->postJson(route('statuses.store'),['body'=> 'Mi primer estado']);

        $response ->assertJson([
            'data'=> ['body'=>'Mi primer estado'],
        ]);
        $this->assertDatabaseHas('statuses',[
            'user_id'=> $user->id,
            'body'=>'Mi primer estado'

        ]);
    }
    /** @test */
    public function un_evento_es_disparado_cuando_un_estado_es_creado(){
        Event::fake([StatusCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $this->withoutExceptionHandling();

        $user=factory(User::class)->create();

        $this->actingAs($user)->postJson(route('statuses.store'),['body'=> 'Mi primer estado']);

        Event::assertDispatched(StatusCreated::class, function ($statusCreatedEvent){

            $this->assertInstanceOf(StatusResource::class, $statusCreatedEvent->status);
            $this->assertTrue(Status::first()->is($statusCreatedEvent->status->resource));
            $this->assertEventChannelName('statuses',$statusCreatedEvent);
            $this->assertEventChannelType('public',$statusCreatedEvent);
            $this->assertDontBroadcastToCurrentUser($statusCreatedEvent);

            return true;
        });
    }

    /** @test */

    public function un_estado_requiere_un_cuerpo(){

        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->postJson(route('statuses.store'),['body'=> '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors'=>['body']

        ]);
    }

    /** @test */

    public function un_estado_requiere_un_minimo_de_caracteres(){

        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->postJson(route('statuses.store'),['body'=> 'asdf']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors'=>['body']

        ]);
    }

}
