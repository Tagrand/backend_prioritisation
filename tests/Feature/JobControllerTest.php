<?php

namespace Tests\Feature;

use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function you_can_get_a_specific_job()
    {
        factory(Job::class, 3)->create();
        $firstJob = factory(Job::class)->create([
            'name' => 'build backend tests',
            'details' => 'build backend tests really well',
            'x' => '4.0',
            'y' => '5.0',
            'completed' => '0',
        ]);

        $response = $this->json('GET', "/api/jobs/{$firstJob->id}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'job' => [
                'name' => 'build backend tests',
                'details' => 'build backend tests really well',
                'x' => '4.00',
                'y' => '5.00',
                'completed' => 0,
                'created_at' => $firstJob->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $firstJob->updated_at->format('Y-m-d H:i:s'),
                'id' => $firstJob->id,
            ],
        ]);
    }

    /** @test */
    function you_can_get_all_the_jobs()
    {
        $jobs = factory(Job::class, 3)->create();

        $response = $this->json('GET', '/api/jobs');

        $response->assertStatus(200);
        $this->assertEquals(3, count($response->json('jobs')));
        $this->assertEquals($jobs->first()->id, $response->json('jobs')[0]['id']);
    }

    /** @test */
    function you_cannot_get_a_job_which_doesnt_exist()
    {
        $response = $this->json('GET', '/api/jobs/1');

        $response->assertStatus(404);
    }
}
