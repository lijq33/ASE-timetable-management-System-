<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Individual;
use App\Timetable;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimetableTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test*/
    public function it_cannot_create_timetable_before_authentication()
    {
        $response = $this->post('/api/timetable',[
            'is_all_day' => 0,
            'start_time' => '2019/12/12T14:00:00Z',
            'end_time' => '2019/12/12T14:30:00Z',

            'description' => 'A short description',
            'subject' => 'Test',
            'location' => '',
            'recurrence_rule' => '',

            'is_appointment' => 0,
            'no_of_slots' => '0',
            'limited_to' => 'private',
            'price' => '0',
        ]);

        $response->assertJson(['error' => 'unauthorize']);
        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_create_timetable_after_authentication()
    {
        $individual = factory(Individual::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);

        $login->assertStatus(200);
            
        $response = $this->post('/api/timetable',[
            'is_all_day' => 0,
            'start_time' => '2019/12/12T14:00:00Z',
            'end_time' => '2019/12/12T14:30:00Z',

            'description' => 'A short description',
            'subject' => 'Test',
            'location' => '',
            'recurrence_rule' => '',

            'is_appointment' => 0,
            'no_of_slots' => '0',
            'limited_to' => 'private',
            'price' => '0',
        ]);

        $response->assertStatus(200);
    }
        
    /** @test*/
    public function it_cannot_retrieve_timetable_before_authentication()
    {
        $individual = factory(Individual::class)
            ->create();
        $timetable = factory(Timetable::class)
            ->create();
        
        $response = $this->get('/api/timetable');

        $response->assertJson(['error' => 'unauthorize']);
        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_retrieve_timetable_after_authentication()
    {
        $individual = factory(Individual::class)
            ->create();

        $timetable = factory(Timetable::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);

        $login->assertStatus(200);

        $response = $this->get('/api/timetable');
        $response->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_delete_timetable_before_authentication()
    {
        $individual = factory(Individual::class)
            ->create();
        $timetable = factory(Timetable::class)
            ->create();

        $response = $this->delete('/api/timetable/'.$timetable->id);

        $response->assertJson(['error' => 'unauthorize']);
        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_delete_timetable_after_authentication()
    {
        $individual = factory(Individual::class)
            ->create();

        $timetable = factory(Timetable::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);

        $this->assertDatabaseHas('timetables', [
            'id' =>  "1",
        ]);

        $response = $this->delete('/api/timetable/'.$timetable->id);
       
        $this->assertDatabaseMissing('timetables', [
            'id' =>  "1",
        ]);
        $response->assertStatus(200);
    }

    /** @test*/
    public function it_cannot_update_timetable_before_authentication()
    {
        $individual = factory(Individual::class)
            ->create();
        $timetable = factory(Timetable::class)
            ->create();

        $response = $this->patch('/api/timetable/'.$timetable->id, [
            'is_all_day' => 0,
            'start_time' => '2019/12/12T14:00:00Z',
            'end_time' => '2019/12/12T14:30:00Z',

            'description' => 'A long description',
            'subject' => 'Test',
            'location' => '',
            'recurrence_rule' => '',

            'is_appointment' => 0,
            'no_of_slots' => '0',
            'limited_to' => 'private',
            'price' => '0',
        ]);

        $response->assertJson(['error' => 'unauthorize']);
        $response->assertStatus(401);
    }

    /** @test*/
    public function it_can_update_timetable_after_authentication()
    {
       
        $individual = factory(Individual::class)
            ->create();
        $timetable = factory(Timetable::class)
            ->create();

        $login = $this->post('/api/auth/login',[
            'email' => $individual->email,
            'password' => '123123',
            'registration_type' => 'individual'
        ]);
    
        $response = $this->patch('/api/timetable/'.$timetable->id, [
            'is_all_day' => 0,
            'start_time' => '2019/12/12T14:00:00Z',
            'end_time' => '2019/12/12T14:30:00Z',

            'description' => 'A long description',
            'subject' => 'Test',
            'location' => '',
            'recurrence_rule' => '',

            'is_appointment' => 0,
            'no_of_slots' => '0',
            'limited_to' => 'private',
            'price' => '0',
        ]);

        $response->assertStatus(200);
    }
}
