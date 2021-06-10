<?php

namespace Tests\Browser;

use App\User;
use App\Models\Status;
use App\Models\Comment;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanCommentStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */

    function los_usuarios_pueden_ver_todos_los_comentarios(){
        $status=factory(Status::class)->create();
        $comments=factory(Comment::class, 2)->create(['status_id'=>$status->id]);

        $this->browse(function (Browser $browser) use($status,$comments) {
            $browser->visit('/')
                    ->waitForText($status->body);

                    foreach($comments as $comment){
                        $browser ->assertSee($comment->body)
                                ->assertSee($comment->user->name);
                    }
            ;
        });
    }

    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function usuarios_autenticados_pueden_comentar_estados()
    {
        $status=factory(Status::class)->create();
        $user=factory(User::class)->create();

        $this->browse(function (Browser $browser) use($user,$status) {
            $comment = 'mi primer comentario';
            $browser->loginAs($user)
                    ->visit('/')
                    ->waitForText($status->body)
                    ->type('comment',$comment)
                    ->press('@comment-btn')
                    ->waitForText($comment)
                    ->assertSee($comment)
            ;
        });
    }

    /** @test */

    public function los_usuarios_pueden_ver_los_comentarios_en_tiempo_real(){

        $status=factory(Status::class)->create();
        $user=factory(User::class)->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use($user,$status) {
            $comment = 'mi primer comentario';

            $browser1->visit('/');

            $browser2->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->type('comment',$comment)
                ->press('@comment-btn')
            ;

            $browser1->waitForText($comment)
                ->assertSee($comment);
        });
    }
}
