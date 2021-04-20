<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $team = $request->user()->currentTeam;
        $totalBenefitCost = Cache::has('totalBenefitCost') ? Cache::get('totalBenefitCost') : 0;
        $employeeCount = Employee::all()->count();

        return Inertia::render('Employees', [
            'employees' => $team->employees()
                ->with('dependents')
                ->orderBy('name')
                ->paginate()
                ->through(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'benefit_cost' => $employee->benefit_cost,
                        'profile_photo_url' => $employee->profile_photo_url,
                        'dependents' => $employee->dependents,
                    ];
                }),
            'average' => $employeeCount > 0 ? $totalBenefitCost / $employeeCount : $totalBenefitCost,
            'total' => $totalBenefitCost,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required', 'dependents' => 'array']);
        $team = $request->user()->currentTeam;
        $employee = $team->employees()->create(['name' => $validated['name'], 'benefit_cost' => $this->benefitCost(1000, $validated['name'])]);
        $employee->benefit_cost += $this->createDependents($employee, $validated['dependents']);
        $employee->save();
        $this->incrementTotalBenefitCost($employee->benefit_cost);

        return redirect(route('employee.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $this->decrementTotalBenefitCost($employee->benefit_cost);
        $employee->delete();

        return redirect(route('employee.index'));
    }

    protected function createDependents(Employee $employee, array $dependents) {
        return collect($dependents)->map(function ($dependent) use ($employee) {
            return $employee->dependents()->create([
                'team_id' => $employee->team_id,
                'name' => $dependent['name'],
                'relation' => $dependent['relation']
            ]);
        })->reduce(fn($sum, $dependent) => $sum + $this->benefitCost(500, $dependent['name']));
    }

    protected function benefitCost(int $amount, $name) {
        $multiplier = strtoupper(substr($name, 0, 1)) === 'A' ? 0.9 : 1.0;

        return $amount * $multiplier;
    }

    protected function incrementTotalBenefitCost(int $amount) {
        if (Cache::has('totalBenefitCost')) Cache::increment('totalBenefitCost', $amount);
        else Cache::forever('totalBenefitCost', $amount);
    }

    protected function decrementTotalBenefitCost(int $amount) {
        if (Cache::has('totalBenefitCost')) Cache::decrement('totalBenefitCost', $amount);
        else Cache::forever('totalBenefitCost', 0);
    }
}
