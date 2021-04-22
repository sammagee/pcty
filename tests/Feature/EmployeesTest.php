<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->withPersonalTeam()->create();
    }

    public function test_can_view_employees()
    {
        $this->user->currentTeam->employees()->saveMany(
            Employee::factory()->count(5)->make()
        );

        dd(Employee::search()->get());

        $this->actingAs($this->user)
            ->get('/')
            ->assertStatus(200)
            ->assertPropCount('employees.data', 5)
            ->assertPropValue('employees.data', function ($employees) {
                $this->assertEquals(
                    ['id', 'name', 'benefit_cost'],
                    array_keys($employees[0])
                );
            });
    }
}
