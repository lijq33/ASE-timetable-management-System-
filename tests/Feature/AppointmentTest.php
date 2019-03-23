<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Individual;
use App\Timetable;
use App\Appointment;
use App\Company;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    // index store destroy

    /** @test*/
    public function it_cannot_create_appointment_before_authentication()
    {
        $timetable = factory(Timetable::class)
            ->states('company_appointment_without_payment')
            ->create();

        $response = $this->post('/api/appointment',[
            'timetable_id' => $timetable->id,
        ]);

        $response->assertJson(['error' => 'unauthorize']);
        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_create_appointment_with_payment_after_authentication()
    {
        //setup
        $company = factory(Company::class)
            ->create();
            
        $timetable = factory(Timetable::class)
            ->states('company_appointment_with_payment')
            ->create();

        //authenticate with individual
        $individual = factory(Individual::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);

        $login->assertStatus(200);

        $response = $this->post('/api/appointment',[
            'timetable_id' => $timetable->id,
            'stripeToken' => 'tok_visa',
        ]);

        $response->assertStatus(200);
    }
    
    /** @test*/
    public function it_can_create_appointment_without_payment_after_authentication()
    {
        //setup
        $company = factory(Company::class)
            ->create();
            
        $timetable = factory(Timetable::class)
            ->states('company_appointment_without_payment')
            ->create();

        //authenticate with individual
        $individual = factory(Individual::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);

        $login->assertStatus(200);

        $response = $this->post('/api/appointment',[
            'timetable_id' => $timetable->id,
            'stripeToken' => null,
        ]);

        $response->assertStatus(200);
    }
    
    /** @test*/
    public function it_cannot_retrieve_appointment_before_authentication()
    {
        $response = $this->get('/api/appointment');

        $response->assertJson(['error' => 'unauthorize']);
        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_retrieve_appointment_after_authentication()
    {
        $individual = factory(Individual::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);

        $login->assertStatus(200);

        $response = $this->get('/api/appointment');
        $response->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_delete_appointment_before_authentication()
    {
        $this->it_can_create_appointment_without_payment_after_authentication();

        //logout
        $logout = $this->post('/api/auth/logout',[
            'registration_type' => 'individual'
        ]);

        $response = $this->delete('/api/appointment/1');

        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_delete_appointment_after_authentication()
    {
        $this->it_can_create_appointment_without_payment_after_authentication();
        $response = $this->delete('/api/appointment/1');

        $response->assertStatus(200);
    }

}
