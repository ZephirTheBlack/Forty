<?php

namespace Tests\Feature;

use App\Models\Friendship;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CanRequestFriendshipTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function los_invitados_no_pueden_enviar_solicitudes_de_amistad()
    {
        $recipient=factory(User::class)->create();

       $response = $this->postJson(route('friendships.store',$recipient));

       $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function podemos_obtener_todas_las_solicitudes_de_amistad_recibidas(){
        $sender= factory(User::class)->create();
        $recipient= factory(User::class)->create();

        $sender->sendFriendRequestTo($recipient);
        factory(Friendship::class, 2)->create();

        $this->actingAs($recipient);

        $response=$this->get(route('accept-friendships.index'));

        $this->assertCount(1, $response->viewData('friendshipRequest'));
    }
    /**
     * @test
     */
    public function los_invitados_no_pueden_eliminar_solicitudes_de_amistad()
    {
        $recipient=factory(User::class)->create();

        $response = $this->deleteJson(route('friendships.destroy',$recipient));

        $response->assertStatus(401);
    }
    /**
     * @test
     */
    public function los_invitados_no_pueden_aceptar_solicitudes_de_amistad()
    {
        $user=factory(User::class)->create();

        $this->postJson(route('accept-friendships.store',$user))
             ->assertStatus(401);

        $this->get(route('accept-friendships.index'))
             ->assertRedirect('login');

    }
    /**
     * @test
     */
    public function los_invitados_no_pueden_denegar_solicitudes_de_amistad()
    {
        $user=factory(User::class)->create();

        $response = $this->deleteJson(route('accept-friendships.destroy',$user));

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function un_usuario_puede_enviar_solicitudes_de_amistad()
    {
        $this->withoutExceptionHandling();

        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        $response=$this->actingAs($sender)->postJson(route('friendships.store',$recipient));

        $response->assertJson([
            'friendship_status'=>'pending'
        ]);

        $this->assertDatabaseHas('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=> 'pending'
        ]);

        $this->actingAs($sender)->postJson(route('friendships.store',$recipient));
        $this->assertCount(1,Friendship::all());
    }
    /**
     * @test
     */
    public function un_usuario_no_puede_enviarse_peticiones_de_amistad_asi_mismo(){

        $sender=factory(User::class)->create();

        $this->actingAs($sender)->postJson(route('friendships.store',$sender));

        $this->assertDatabaseMissing('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$sender->id,
            'status'=> 'pending'
        ]);
    }
    /**
     * @test
     */
    public function un_usuario_puede_aceptar_solicitudes_de_amistad()
    {
        $this->withoutExceptionHandling();

        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=> 'pending'
        ]);

        $response=$this->actingAs($recipient)->postJson(route('accept-friendships.store',$sender));

        $response->assertJson([
            'friendship_status'=>'accepted'
        ]);

        $this->assertDatabaseHas('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=> 'accepted'
        ]);
    }
    /**
     * @test
     */
    public function un_usuario_puede_denegar_solicitudes_de_amistad()
    {

        $this->withoutExceptionHandling();

        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=> 'pending'
        ]);

        $response=$this->actingAs($recipient)->deleteJson(route('accept-friendships.destroy',$sender));

        $response->assertJson([
            'friendship_status'=>'denied'
        ]);

        $this->assertDatabaseHas('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=> 'denied'
        ]);
    }
    /**
     * @test
     */
    public function un_usuario_puede_eliminar_solicitudes_de_amistad_despues_de_enviarla()
    {
        $this->withoutExceptionHandling();

        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);

        $response=$this->actingAs($sender)->deleteJson(route('friendships.destroy',$recipient));
        $response->assertJson([
            'friendship_status'=>'deleted'
        ]);
        $this->assertDatabaseMissing('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);

    }
    /**
     * @test
     */
    public function un_usuario_puede_eliminar_solicitudes_de_amistad_despues_de_recibirla()
    {
        $this->withoutExceptionHandling();

        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);

        $response=$this->actingAs($recipient)->deleteJson(route('friendships.destroy',$sender));
        $response->assertJson([
            'friendship_status'=>'deleted'
        ]);
        $this->assertDatabaseMissing('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
        ]);
    }
    /**
     * @test
     */
    public function un_usuario_no_puede_eliminar_solicitudes_de_amistad_denegadas()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=>'denied'
        ]);

        $response=$this->actingAs($sender)->deleteJson(route('friendships.destroy',$recipient));

        $response->assertJson([
            'friendship_status'=>'denied'
        ]);

        $this->assertDatabaseHas('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=>'denied'
        ]);
    }
    /**
     * @test
     */
    public function un_usuario_no_puede_eliminar_solicitudes_de_amistad_denegadas_que_ellos_recibieron()
    {
        $sender=factory(User::class)->create();
        $recipient=factory(User::class)->create();

        Friendship::create([
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=>'denied'
        ]);

        $response=$this->actingAs($recipient)->deleteJson(route('friendships.destroy',$sender));

        $response->assertJson([
            'friendship_status'=>'deleted'
        ]);

        $this->assertDatabaseMissing('friendships',[
            'sender_id'=>$sender->id,
            'recipient_id'=>$recipient->id,
            'status'=>'denied'
        ]);
    }
}
