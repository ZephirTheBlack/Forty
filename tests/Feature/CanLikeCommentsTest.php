<?php

namespace Tests\Feature;

use App\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanLikeCommentsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function usuarios_invitados_no_pueden_dar_like_a_los_comentarios()
    {
        $comment = factory(Comment::class)->create();
        $response = $this->postJson(route('comments.likes.store', $comment));
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function un_usuario_autenticado_puede_dar_like_y_dislike_a_los_comentarios()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $user=factory(User::class)->create();
        $comment=factory(Comment::class)->create();

        $this->assertCount(0, $comment->likes);

        $response= $this->actingAs($user)->postJson( route('comments.likes.store',$comment));

        $response->assertJsonFragment([
            'likes_count' => 1
        ]);

        $this->assertCount(1, $comment->fresh()->likes);
        $this->assertDatabaseHas('likes',['user_id'=> $user->id,]);

        $response=$this->actingAs($user)->deleteJson( route('comments.likes.destroy',$comment));
        $response->assertJsonFragment([
            'likes_count' => 0
        ]);

        $this->assertCount(0, $comment->fresh()->likes);
        $this->assertDatabaseMissing('likes',['user_id'=> $user->id,]);
    }
}
