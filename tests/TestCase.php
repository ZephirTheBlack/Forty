<?php

namespace Tests;

use App\Models\Comment;
use App\Traits\HasLikes;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertClassUsesTrait($trait,$class){
        $this->assertArrayHasKey($trait,class_uses($class),'La clase comment debe utilizar el trait Haslikes');

    }

    protected function assertDontBroadcastToCurrentUser($event, $socketId = 'socket-id'){
        $this->assertInstanceOf(ShouldBroadcast::class, $event);

        $this->assertEquals(
            $socketId, //se genera con Broadcast::shouldReceive('socket')->andReturn('socket-id');
            $event->socket,
            'El evento'.get_class($event).'debe llamar al metodo dontBroadcastToCurrentUser() en el constructor.');
    }

    protected function assertEventChannelType($channeltype,$event){

        $types=[
            'public'=>  \Illuminate\Broadcasting\Channel::class,
            'private'=>  \Illuminate\Broadcasting\PrivateChannel::class,
            'presence'=>  \Illuminate\Broadcasting\PresenceChannel::class,
        ];

        $this->assertEquals($types[$channeltype], get_class($event->broadcastOn()));

    }

    protected function assertEventChannelName($channelName,$event){
        $this->assertEquals($channelName, $event->broadcastOn()->name);
    }

}
