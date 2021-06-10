<?php

namespace Tests\Unit\Http\Resources;


use App\Models\Comment;
use App\Models\Status;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\StatusResource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusResourceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function el_recurso_de_un_estado_debe_tener_los_campos_necesarios()
    {
        $status=factory(Status::class)->create();
        factory(Comment::class)->create(['status_id'=>$status->id]);
        $statusResource = StatusResource::make($status)->resolve();

        $this->assertEquals($status->id, $statusResource['id']);
        $this->assertEquals($status->body, $statusResource['body']);
        $this->assertEquals($status->created_at->diffForHumans(), $statusResource['ago']);
        $this->assertEquals(false, $statusResource['is_liked']);
        $this->assertEquals(0, $statusResource['likes_count']);
        $this->assertEquals(CommentResource::class, $statusResource['comments']->collects);
        $this->assertInstanceOf(Comment::class,$statusResource['comments']->first()->resource);
        $this->assertInstanceOf(UserResource::class, $statusResource['user']);
        $this->assertInstanceOf(User::class,$statusResource['user']->resource);
    }
}
