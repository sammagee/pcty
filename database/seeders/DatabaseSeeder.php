<?php

namespace Database\Seeders;

use App\Models\Dependent;
use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(int $number, bool $withUser)
    {
        if ($withUser) {
            $user = User::factory()->withTeam('PCTY')->create(['email' => 'hi@sammagee.me', 'name' => 'Sam']);
            $user->current_team_id = Team::where('user_id', $user->id)->first()->id;
            $user->save();
        } else {
            $user = User::first();
        }

        $team = $user->currentTeam;
        $team->employees()->saveMany(Employee::factory($number)->make());
        $team->employees->each(function ($employee) use ($team) {
            $dependents = Dependent::factory(mt_rand(0, 5))->create(['employee_id' => $employee->id, 'team_id' => $team->id]);
            $dependents_cost = $dependents->reduce(function ($sum, $dependent) {
                $multiplier = strtoupper(substr($dependent->name, 0, 1)) === 'A' ? 0.9 : 1.0;

                return $sum + (500 * $multiplier * 100);
            });
            $employee->benefit_cost += $dependents_cost;
            $employee->save();
        });
    }
}
