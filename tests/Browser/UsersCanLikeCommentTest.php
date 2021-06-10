<?php

namespace Tests\Browser;

use App\User;
use App\Models\Comment;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanLikeCommentTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     * @throws \Throwable
     */
    public function usuarios_pueden_dar_like_y_dislike_a_los_comentarios()
    {
        $user= factory(User::class)->create();
        $comment= factory(Comment::class)->create();

        $this->browse(function (Browser $browser) use ($user, $comment){
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count',0)
                ->press('@comment-like-btn')
                ->waitForText('Te Gusta')
                ->assertSee('Te Gusta')
                ->assertSeeIn('@comment-likes-count',1)

                ->press('@comment-like-btn')
                ->waitForText('Me Gusta')
                ->assertSee('Me Gusta')
                ->pause(1000)
                ->assertSeeIn('@comment-likes-count',0)
            ;
        });
    }
    /**
     * @test
     * @throws \Throwable
     */
    public function usuarios_pueden_ver_los_likes_y_dislikes_a_los_comentarios_en_tiempo_real()
    {
        $user= factory(User::class)->create();
        $comment= factory(Comment::class)->create();

        $this->browse(function (Browser $browser1,Browser $browser2) use ($user, $comment){
            $browser1->visit('/');

            $browser2->loginAs($user)
                ->visit('/')
                ->maximize()
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count',0)
                ->press('@comment-like-btn')
                ->waitForText('Te Gusta')
            ;
            $browser1->assertSeeIn('@comment-likes-count',1);

            $browser2->press('@comment-like-btn')
                ->waitForText('Me Gusta');

            $browser1->pause(1000)->assertSeeIn('@comment-likes-count',0);
        });
    }
}
