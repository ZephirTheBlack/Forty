<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Http\Resources\UserResource;
use App\Models\Status;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function el_comentario_de_un_estado_debe_tener_los_campos_necesarios()
    {
        $comment=factory(Status::class)->create();

        $commentResource = CommentResource::make($comment)->resolve();

        $this->assertEquals($comment->id, $commentResource['id']);

        $this->assertEquals($comment->body, $commentResource['body']);

        $this->assertEquals(0, $commentResource['likes_count']);

        $this->assertEquals(false, $commentResource['is_liked']);

        $this->assertInstanceOf(UserResource::class, $commentResource['user']);
        $this->assertInstanceOf(User::class,$commentResource['user']->resource);
    }
}
