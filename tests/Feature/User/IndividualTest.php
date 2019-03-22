<?php

namespace Tests\Feature\User;

use Tests\TestCase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Individual;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndividualTest extends TestCase
{
    use DatabaseMigrations, WithFaker;
   
    /** @test*/
    public function it_can_log_a_individual_in()
    {
        $individual = factory(Individual::class)
            ->create();

        $this->assertDatabaseHas('individuals', [
            'email' => $individual->email,    
            'nric' => $individual->nric,
        ]);

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'Individual'
        ]);

        $login->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_log_a_individual_in_with_incorrect_credentials()
    {
        $individual = factory(Individual::class)
            ->create();

        $this->assertDatabaseHas('individuals', [
            'email' => $individual->email,
        ]);
            
        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '1234567',
            'registration_type' => 'Individual'
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }

    /** @test*/
    public function it_cannot_log_a_individual_in_as_company()
    {
        $individual = factory(Individual::class)
            ->create();

        $this->assertDatabaseHas('individuals', [
            'email' => $individual->email,
        ]);
            
        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '1234567',
            'registration_type' => 'company'
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }
    
    /** @test */
    public function it_can_register_an_individual_account()
    {
        $response = $this->post('/api/auth/register', [
            'name' => $this->faker->firstName(),
            'nric' =>  "S9765432Z",
            'password' => '123456',
            'password_confirmation' => '123456',
            'email' => $this->faker->email(),
            'telephone_number' => random_int(90000000 ,99999999),
            'registration_type' => 'individual',
        ]);

        $response->assertStatus(200);

        $this->assertDatabasehas('individuals', [
            'nric' =>  "S9765432Z",
        ]);

    }

    /** @test */
    public function it_cannot_register_individual_account_with_same_email()
    {
        $individual = factory(Individual::class)
            ->create();

        $this->assertDatabasehas('individuals', [
            'email' => $individual->email,
        ]);

        $response = $this->post('/api/auth/register', [
            'email' => $individual->email,

            'name' => $this->faker->firstName(),
            'nric' =>  "S9765432Z",
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_same_nric()
    {
        $individual = factory(Individual::class)
            ->create();

        $this->assertDatabasehas('individuals', [
            'nric' => $individual->nric,
        ]);

        $response = $this->post('/api/auth/register', [
            'nric' => $individual->nric,

            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_incorrect_nric_format()
    {

        //Short NRIC
        $response = $this->post('/api/auth/register', [
            'nric' => 'S976543Z',

            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S976543Z",
        ]);

        //Long NRIC
        $response = $this->post('/api/auth/register', [
            'nric' =>  "S97654322Z",
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S97654322Z",
        ]);
        
        //Empty NRIC
        $response = $this->post('/api/auth/register', [
            'nric' =>  "",
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000 ,99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'email' =>  "test@test.com",
        ]);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_incorrect_telephone_format()
    {
        //Short telephone
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(9000000, 9999999),

            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);

        //Long telephone
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(900000000, 999999999),

            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
        
        //Alphable in telephone
           $response = $this->post('/api/auth/register', [
            'telephone_number' => 'a'.random_int(9000000, 9999999),

            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_mismatched_password()
    {
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123455',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_password_shorter_than_6_character()
    {
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_password_longer_than_30_character()
    {
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '1234567890123456789012345678901',
            'password_confirmation' => '1234567890123456789012345678901',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
    }
    
    /** @test */
    public function it_cannot_register_individual_account_with_wrong_email_format()
    {
        $response = $this->post('/api/auth/register', [
            'email' =>  'test.com',
            'telephone_number' => random_int(90000000, 99999999),
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
    }

    /** @test */
    public function it_cannot_register_individual_account_with_any_empty_fields()
    {
        //empty nric
        $response = $this->post('/api/auth/register', [
            'nric' => '',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000, 99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'email' =>  "test@test.com",
        ]);

        //empty name
        $response = $this->post('/api/auth/register', [
            'nric' => 'S9765432Z',
            'name' => '',
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000, 99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
        
        //empty email
        $response = $this->post('/api/auth/register', [
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  '',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000, 99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);

        //empty password
        $response = $this->post('/api/auth/register', [
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '',
            'password_confirmation' => '123456',
            'telephone_number' => random_int(90000000, 99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);

        //empty password confirmation
        $response = $this->post('/api/auth/register', [
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '',
            'telephone_number' => random_int(90000000, 99999999),
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);

        //empty telephone
        $response = $this->post('/api/auth/register', [
            'nric' => 'S9765432Z',
            'name' => $this->faker->firstName(),
            'email' =>  'test@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'telephone_number' => '',
            'registration_type' => 'individual',
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('individuals', [
            'nric' =>  "S9765432Z",
        ]);
    }

}