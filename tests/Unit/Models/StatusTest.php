<?php

namespace Tests\Unit\Models;

use App\Models\Status;
use App\Models\Like;
use App\Models\Comment;
use App\Traits\HasLikes;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function un_estado_pertenece_a_un_usuario()
    {
        $status=factory(Status::class)->create();

        $this->assertInstanceOf(User::class, $status->user);
    }

    /**
     * @test
     */
    public function un_estado_tiene_muchos_comentarios(){
        $status=factory(Status::class)->create();

        factory(Comment::class)->create(['status_id'=>$status->id]);

        $this->assertInstanceOf(Comment::class, $status->comments->first());
    }

    /**
     * @test
     */
    public function el_modelo_comentario_debe_utilizar_el_trait_haslikes()
    {
        $this->assertClassUsesTrait(HasLikes::class,Status::class);
    }
}
