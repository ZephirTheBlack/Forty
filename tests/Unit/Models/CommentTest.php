<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\status;
use App\Traits\HasLikes;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function un_comentario_pertenece_a_un_usuario()
    {
       $comment = factory(Comment::class)->create();

       $this->assertInstanceOf(User::class,$comment->user);
    }
    /**
     * @test
     */
    public function un_comentario_pertenece_a_un_estado()
    {
        $comment = factory(Comment::class)->create();

        $this->assertInstanceOf(Status::class,$comment->status);
    }
    /**
     * @test
     */
    public function el_modelo_comentario_debe_utilizar_el_trait_haslikes()
    {
        $this->assertClassUsesTrait(HasLikes::class,Comment::class);
    }

    /**
     * @test
     */

    public function un_comentario_debe_tener_un_path(){
        $comment = factory(Comment::class)->create();
        $this->assertEquals(route('statuses.show',$comment->status_id).'#comment-'.$comment->id,$comment->path());
    }
}
