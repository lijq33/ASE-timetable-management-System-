<?php

namespace Tests\Feature\User;

use Tests\TestCase;
USE Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Company;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use DatabaseMigrations, WithFaker;
   
    /** @test*/
    public function it_can_log_a_company_in()
    {
        $user = factory(Company::class)
            ->create();

        $this->assertDatabaseHas('companies', [
            'email' => $user->email,
        ]);

        $login = $this->post('/api/auth/login',[
            'email' => $user->email,
            'password' => '123123',
            'registration_type' => 'companies'
        ]);

        $login->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_log_a_company_in_with_incorrect_credentials()
    {
        $user = factory(Company::class)
            ->create();

        $this->assertDatabaseHas('companies', [
            'email' => $user->email,
        ]);
            
        $login = $this->post('/api/auth/login',[
            'email' => $user->email,
            'password' => '1234567',
            'registration_type' => 'company'
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }

    /** @test*/
    public function it_cannot_log_a_company_in_as_individual()
    {
        $user = factory(Company::class)
            ->create();

        $this->assertDatabaseHas('companies', [
            'email' => $user->email,
        ]);
            
        $login = $this->post('/api/auth/login',[
            'email' => $user->email,
            'password' => '1234567',
            'registration_type' => 'individual'
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }

    /** @test */
    public function it_can_register_an_company_account()
    {
        $email = $this->faker->email();

        $response = $this->post('/api/auth/register', [
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',

            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            
            'email' => $email,
            'password' => '12341234',
            'password_confirmation' => '12341234',
            'telephone_number' => random_int(90000000 ,99999999), 
            
            'registration_type' => 'company'
        ]);

        $response->assertStatus(200);
        $this->assertDatabasehas('companies', [
            'email' => $email,
        ]);
    }
    
    /** @test */
    public function it_cannot_register_company_account_with_same_email()
    {
        $user = factory(Company::class)
            ->create();

        $this->assertDatabasehas('companies', [
            'email' => $user->email,
        ]);

        $response = $this->post('/api/auth/register', [
            'email' => $user->email,

            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '12341234',
            'password_confirmation' => '12341234',
            'telephone_number' => random_int(90000000 ,99999999), 
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);
    }










    /** @test */
    public function it_cannot_register_user_account_with_incorrect_nric_format()
    {
  
    }


    /** @test */
    public function it_cannot_register_user_account_with_incorrect_telephone_format()
    {
       
    }

    /** @test */
    public function it_cannot_register_user_account_with_mismatched_password()
    {
        
    }

    /** @test */
    public function it_cannot_register_user_account_with_password_shorter_than_6_character()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_password_longer_than_30_character()
    {

    }
    
    /** @test */
    public function it_cannot_register_user_account_with_wrong_email_format()
    {

    }

    /** @test */
    public function it_cannot_register_user_account_with_any_empty_fields()
    {

    }

}