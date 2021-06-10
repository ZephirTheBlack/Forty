<?php

namespace Tests\Unit\Listeners;

use App\Events\ModelLiked;
use App\Models\Status;
use App\Notifications\NewLikeNotification;
use App\User;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendNewLikeNotificationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function una_notificacion_es_enviada_cuando_un_usuario_recibe_un_like()
    {
        Notification::fake();

        $statusOwner = factory(User::class)->create();
        $likeSender = factory(User::class)->create();

        $status = factory(Status::class)->create(['user_id'=>$statusOwner->id]);

        $status->likes()->create([
            'user_id'=> $likeSender->id
        ]);

        ModelLiked::dispatch($status, $likeSender);

        Notification::assertSentTo($statusOwner,
            NewLikeNotification::class, function ($notification,$channels) use ($status,$likeSender){
                $this->assertContains('database',$channels);
                $this->assertContains('broadcast',$channels);
                $this->assertTrue($notification->likeSender->is($likeSender));
                $this->assertTrue($notification->model->is($status));
                $this->assertInstanceOf(BroadcastMessage::class,$notification->toBroadcast($status->user));

                return true;
        });
    }
}
