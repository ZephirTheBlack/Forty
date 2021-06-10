<?php

namespace Tests\Unit\Listeners;

use App\Events\CommentCreated;
use App\Models\Comment;
use App\Models\Status;
use App\Notifications\NewCommentNotification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNewCommentNotificationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function una_notificacion_es_enviada_cuando_un_estado_recibe_un_comentario()
    {
        Notification::fake();

        $status= factory(Status::class)->create();
        $comment=factory(Comment::class)->create(['status_id'=>$status->id]);

        CommentCreated::dispatch($comment);

        Notification::assertSentTo(
            $status->user,
            NewCommentNotification::class,
            function ($notification,$channels) use($comment, $status){
                $this->assertContains('database',$channels);
                $this->assertContains('broadcast',$channels);
                $this->assertTrue($notification->comment->is($comment));
                $this->assertInstanceOf(BroadcastMessage::class,$notification->toBroadcast($status->user));
                return true;
            }
        );
    }
}
