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
        $company = factory(Company::class)
            ->create();

        $this->assertDatabaseHas('companies', [
            'email' => $company->email,
        ]);

        $login = $this->post('/api/auth/login',[
            'email' => $company->email,
            'password' => '123123',
            'registration_type' => 'companies'
        ]);

        $login->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_log_a_company_in_with_incorrect_credentials()
    {
        $company = factory(Company::class)
            ->create();

        $this->assertDatabaseHas('companies', [
            'email' => $company->email,
        ]);
            
        $login = $this->post('/api/auth/login',[
            'email' => $company->email,
            'password' => '1234567',
            'registration_type' => 'company'
        ]);

        $login->assertStatus(401);
        $login->assertJson(['error'  => "Unauthorized"]);
    }

    /** @test*/
    public function it_cannot_log_a_company_in_as_individual()
    {
        $company = factory(Company::class)
            ->create();

        $this->assertDatabaseHas('companies', [
            'email' => $company->email,
        ]);
            
        $login = $this->post('/api/auth/login',[
            'email' => $company->email,
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
        $company = factory(Company::class)
            ->create();

        $this->assertDatabasehas('companies', [
            'email' => $company->email,
        ]);

        $response = $this->post('/api/auth/register', [
            'email' => $company->email,

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
    public function it_cannot_register_companies_account_with_incorrect_telephone_format()
    {
        $email = $this->faker->email();

        //Short telephone
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(9000000, 9999999),

            'email' => $email,
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '12341234',
            'password_confirmation' => '12341234',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);

        //Long telephone
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(900000000, 999999999),

            'email' => $email,
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '12341234',
            'password_confirmation' => '12341234',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);
        
        //Alphable in telephone
           $response = $this->post('/api/auth/register', [
            'telephone_number' => 'a'.random_int(9000000, 9999999),

            'email' => $email,
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '12341234',
            'password_confirmation' => '12341234',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);
    }

    /** @test */
    public function it_cannot_register_companies_account_with_mismatched_password()
    {
        $email = $this->faker->email();

        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),

            'email' => $email,
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '1234567',
            'password_confirmation' => '7654321',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);
    }

    /** @test */
    public function it_cannot_register_companies_account_with_password_shorter_than_6_character()
    {
        $email = $this->faker->email();

        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),

            'email' => $email,
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '12345',
            'password_confirmation' => '12345',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);
    }

    /** @test */
    public function it_cannot_register_companies_account_with_password_longer_than_30_character()
    {
        $email = $this->faker->email();

        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),

            'email' => $email,
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '1234567890123456789012345678901',
            'password_confirmation' => '1234567890123456789012345678901',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);
    }
    
    /** @test */
    public function it_cannot_register_companies_account_with_wrong_email_format()
    {
        $response = $this->post('/api/auth/register', [
            'telephone_number' => random_int(90000000, 99999999),

            'email' => 'test.com',
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => 'test.com',
        ]);
    }

    /** @test */
    public function it_cannot_register_companies_account_with_any_empty_fields()
    {
        $email = $this->faker->email();
        
        //missing email
        $response = $this->post('/api/auth/register', [
            'email' => '',
            'telephone_number' => random_int(90000000, 99999999),
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'id' => '1',
        ]);

        //missing telephone
        $response = $this->post('/api/auth/register', [
            'email' => $email,
            'telephone_number' => '',
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);

        //missing company name
        $response = $this->post('/api/auth/register', [
            'email' => $email,
            'telephone_number' => '98765432',
            'company_name' => '',
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);

         //missing company type
         $response = $this->post('/api/auth/register', [
            'email' => $email,
            'telephone_number' => '98765432',
            'company_name' => $this->faker->firstName(),
            'company_type' => '',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);

         //missing industry type
         $response = $this->post('/api/auth/register', [
            'email' => $email,
            'telephone_number' => '98765432',
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => '',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);

        //missing password
        $response = $this->post('/api/auth/register', [
            'email' => $email,
            'telephone_number' => '98765432',
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '',
            'password_confirmation' => '123456',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);

         //missing password confirmation
         $response = $this->post('/api/auth/register', [
            'email' => $email,
            'telephone_number' => '98765432',
            'company_name' => $this->faker->firstName(),
            'company_type' => 'Public company',
            'industry_type' => 'Accounting',
            'website' => 'www.test.com',
            'tagline' => 'test',
            'logo' => 'null',
            'password' => '123456',
            'password_confirmation' => '',
            'registration_type' => 'company'
        ]);
        
        $response->assertStatus(422);

        $this->assertDatabaseMissing('companies', [
            'email' => $email,
        ]);
    }

}