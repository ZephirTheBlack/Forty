<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function los_usuarios_pueden_registrarse()
    {

        $this->get(route('register'))->assertSuccessful();

       $response = $this->post(route('register'),$this->userValidData());
       $response->assertRedirect('/');

        $this->assertDatabaseHas('users',[
            'name'=>'mariano',
            'first_name'=>'matola',
            'last_name'=>'rubia',
            'email'=> 'marianoelpeluca@gmail.com',
        ]);

        $this->assertTrue(Hash::check('secret',User::first()->password));
    }

    /**
     * @test
     */

    public function el_nombre_es_obligatorio(){
        $this->post(route('register'),
            $this->userValidData(['name'=>null]))
            ->assertSessionHasErrors('name');

    }


    /**
     * @test
     */
    public function el_nombre_debe_ser_unico_en_la_bd(){

        factory(User::class)->create(['name'=>'KevinRodriguez']);

        $this->post(route('register'),
            $this->userValidData(['name'=>'KevinRodriguez']))
            ->assertSessionHasErrors('name');

    }

    /**
     * @test
     */
    public function el_nombre_solo_contiene_letras_y_numeros(){

        $this->post(route('register'),
            $this->userValidData(['name'=>'Kevin Rodriguez']))
            ->assertSessionHasErrors('name');

        $this->post(route('register'),
            $this->userValidData(['name'=>'Kevin Rodriguez2']))
            ->assertSessionHasErrors('name');

    }
    /**
     * @test
     */
    public function el_nombre_debe_ser_una_string(){
        $this->post(route('register'),
            $this->userValidData(['name'=>1234]))
            ->assertSessionHasErrors('name');

    }
    /**
     * @test
     */
    public function el_nombre_debe_tener_mas_de_60(){
        $this->post(route('register'),
            $this->userValidData(['name'=>str_random(61)]))
            ->assertSessionHasErrors('name');

    }

    /**
     * @test
     */
    public function el_nombre_debe_tener_minimo_3_caracteres(){
        $this->post(route('register'),
            $this->userValidData(['name'=>'as']))
            ->assertSessionHasErrors('name');

    }
    /**
     * @test
     */

    public function el_primer_apellido_es_obligatorio(){
        $this->post(route('register'),
            $this->userValidData(['first_name'=>null]))
            ->assertSessionHasErrors('first_name');

    }
    /**
     * @test
     */
    public function el_primer_apellido_contiene_minimo_3_caracteres(){

        $this->post(route('register'),
            $this->userValidData(['first_name'=>'as']))
            ->assertSessionHasErrors('first_name');

    }

    /**
     * @test
     */
    public function el_primer_apellido_solo_contiene_letras(){

        $this->post(route('register'),
            $this->userValidData(['first_name'=>'Kevin2']))
            ->assertSessionHasErrors('first_name');

        $this->post(route('register'),
            $this->userValidData(['first_name'=>'Kevin<>']))
            ->assertSessionHasErrors('first_name');

    }
    /**
     * @test
     */
    public function el_primer_apellido_debe_ser_una_string(){
        $this->post(route('register'),
            $this->userValidData(['first_name'=>1234]))
            ->assertSessionHasErrors('first_name');

    }
    /**
     * @test
     */
    public function el_primer_apellido_debe_tener_mas_de_60(){
        $this->post(route('register'),
            $this->userValidData(['first_name'=>str_random(61)]))
            ->assertSessionHasErrors('first_name');

    }
    /**
     * @test
     */

    public function el_segundo_apellido_es_obligatorio(){
        $this->post(route('register'),
            $this->userValidData(['last_name'=>null]))
            ->assertSessionHasErrors('last_name');

    }
    /**
     * @test
     */
    public function el_segundo_apellido_contiene_minimo_3_caracteres(){

        $this->post(route('register'),
            $this->userValidData(['last_name'=>'as']))
            ->assertSessionHasErrors('last_name');

    }
    /**
     * @test
     */
    public function el_segundo_apellido_solo_contiene_letras(){

        $this->post(route('register'),
            $this->userValidData(['last_name'=>'Kevin2']))
            ->assertSessionHasErrors('last_name');

        $this->post(route('register'),
            $this->userValidData(['last_name'=>'Kevin<>']))
            ->assertSessionHasErrors('last_name');

    }
    /**
     * @test
     */
    public function el_segundo_apellido_debe_ser_una_string(){
        $this->post(route('register'),
            $this->userValidData(['last_name'=>1234]))
            ->assertSessionHasErrors('last_name');

    }
    /**
     * @test
     */
    public function el_segundo_apellido_debe_tener_mas_de_60(){
        $this->post(route('register'),
            $this->userValidData(['last_name'=>str_random(61)]))
            ->assertSessionHasErrors('last_name');

    }
    /**
     * @test
     */

    public function el_email_es_obligatorio(){
        $this->post(route('register'),
            $this->userValidData(['email'=>null]))
            ->assertSessionHasErrors('email');

    }
    /**
     * @test
     */
    public function el_email_debe_tener_una_estructura_valida(){
        $this->post(route('register'),
            $this->userValidData(['email'=>'invalidemail']))
            ->assertSessionHasErrors('email');

    }

    /**
     * @test
     */
    public function el_email_debe_ser_unico_en_la_bd(){

        factory(User::class)->create(['email'=>'kevin@email.com']);

        $this->post(route('register'),
            $this->userValidData(['email'=>'kevin@email.com']))
            ->assertSessionHasErrors('email');

    }

    /**
     * @test
     */

    public function la_contraseña_es_obligatorio(){
        $this->post(route('register'),
            $this->userValidData(['password'=>null]))
            ->assertSessionHasErrors('password');

    }
    /**
     * @test
     */
    public function la_contraseña_debe_ser_una_string(){
        $this->post(route('register'),
            $this->userValidData(['password'=>1234]))
            ->assertSessionHasErrors('password');

    }
    /**
     * @test
     */
    public function la_contraseña_debe_tener_minimo_6_caracteres(){
        $this->post(route('register'),
            $this->userValidData(['password'=>'asdfg']))
            ->assertSessionHasErrors('password');

    }
    /**
     * @test
     */
    public function la_contraseña_debe_ser_confirmada(){
        $this->post(route('register'),
            $this->userValidData(['password'=>'secret','password_confirmation'=>null]))
            ->assertSessionHasErrors('password');

    }

    /**
     * @param array $overrides
     * @return array
     */
    public function userValidData($overrides=[]): array
    {
        return array_merge([
            'name' => 'mariano',
            'first_name' => 'matola',
            'last_name' => 'rubia',
            'email' => 'marianoelpeluca@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ],$overrides);
    }
}
