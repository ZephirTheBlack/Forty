<?php

namespace Tests\Feature;

use App\Events\CommentCreated;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */

    public function los_invitados_no_pueden_crear_comentarios()
    {
        $status=factory(Status::class)->create();
        $comment = ['body'=> 'mi primer comentario'];
        $response=$this->postJson(route('statuses.comments.store',$status),$comment);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function un_usuario_autenticado_puede_comentar_estados()
    {

        $status=factory(Status::class)->create();
        $user=factory(User::class)->create();
        $comment = ['body'=> 'mi primer comentario'];

        $response=$this->actingAs($user)
            ->postJson(route('statuses.comments.store',$status),$comment);

        $response->assertJson([
            'data'=>['body'=> $comment['body']]
        ]);

        $this->assertDatabaseHas('comments',[
            'user_id'=>$user->id,
            'status_id'=>$status->id,
            'body'=> $comment['body']
        ]);
    }
    /** @test */
    public function un_evento_es_disparado_cuando_un_comentario_es_creado(){

        Event::fake(CommentCreated::class);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $status=factory(Status::class)->create();
        $user=factory(User::class)->create();
        $comment = ['body'=> 'mi primer comentario'];

        $this->actingAs($user)->postJson(route('statuses.comments.store',$status),$comment);

        Event::assertDispatched(CommentCreated::class, function ($commentStatusEvent){

            $this->assertInstanceOf(CommentResource::class, $commentStatusEvent->comment);
            $this->assertTrue(Comment::first()->is($commentStatusEvent->comment->resource));
            $this->assertEventChannelName("statuses.{$commentStatusEvent->comment->status_id}.comments",$commentStatusEvent);
            $this->assertEventChannelType('public',$commentStatusEvent);
            $this->assertDontBroadcastToCurrentUser($commentStatusEvent);

            return true;
        });

    }
    /** @test */

    public function un_comentario_requiere_un_cuerpo(){

        $status=factory(Status::class)->create();
        $user=factory(User::class)->create();
        $this->actingAs($user);

        $response=$this->postJson(route('statuses.comments.store', $status),['body'=> '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors'=>['body']

        ]);
    }
}
