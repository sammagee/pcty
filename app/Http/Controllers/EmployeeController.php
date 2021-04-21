<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $totalBenefitCost = $this->getTotalBenefitCost($team);
        $employeesCount = $team->employees()->count();

        return Inertia::render('Employees', [
            'search' => $request->search,
            'employees' => Employee::search($request->search)
                ->where('team_id', $team->id)
                ->orderBy('name')
                ->query(fn ($employee) => $employee->with('dependents'))
                ->paginate(1)
                ->withQueryString()
                ->through(fn ($employee) => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'benefit_cost' => $employee->benefit_cost,
                    'profile_photo_url' => $employee->profile_photo_url,
                    'dependents' => $employee->dependents,
                ]),
            'average' => $employeesCount > 0 ? $totalBenefitCost / $employeesCount : $totalBenefitCost,
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
        $employee->benefit_cost += $this->updateDependents($employee, $validated['dependents']);
        $employee->save();
        $this->incrementTotalBenefitCost($team->id, $employee->benefit_cost);

        return redirect()->back();
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate(['name' => 'required', 'dependents' => 'array']);
        $team = $request->user()->currentTeam;
        $this->decrementTotalBenefitCost($team->id, $employee->benefit_cost);
        $employee->update(['name' => $validated['name'], 'benefit_cost' => $this->benefitCost(1000, $validated['name'])]);
        $employee->benefit_cost += $this->updateDependents($employee, $validated['dependents']);
        $employee->save();
        $this->incrementTotalBenefitCost($team->id, $employee->benefit_cost);

        return redirect()->back();
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $this->decrementTotalBenefitCost($employee->team_id, $employee->benefit_cost);
        $employee->delete();

        return redirect()->back();
    }

    protected function updateDependents(Employee $employee, array $dependents) {
        $employee->dependents()->delete();

        return collect($dependents)->map(function ($dependent) use ($employee) {
            return $employee->dependents()->create([
                'team_id' => $employee->team_id,
                'name' => $dependent['name'],
                'relation' => $dependent['relation'] ?? 'other',
            ]);
        })->reduce(fn($sum, $dependent) => $sum + $this->benefitCost(500, $dependent['name']));
    }

    protected function benefitCost(int $amount, $name) {
        $multiplier = strtoupper(substr($name, 0, 1)) === 'A' ? 0.9 : 1.0;

        return $amount * $multiplier * 100;
    }

    protected function incrementTotalBenefitCost($id, int $amount) {
        $key = 'total_benefit_cost:'.$id;
        if (Cache::has($key)) Cache::increment($key, $amount);
        else Cache::forever($key, $amount);
    }

    protected function decrementTotalBenefitCost($id, int $amount) {
        $key = 'total_benefit_cost:'.$id;
        if (Cache::has($key)) Cache::decrement($key, $amount);
        else Cache::forever($key, 0);
    }

    protected function getTotalBenefitCost($team) {
        if (Cache::has('total_benefit_cost:'.$team->id)) return Cache::get('total_benefit_cost:'.$team->id);
        
        $total = $team->employees->reduce(fn($sum, $employee) => $sum + $this->benefitCost(1000, $employee['name']));
        $total += $team->dependents->reduce(fn($sum, $dependent) => $sum + $this->benefitCost(500, $dependent['name']));

        Cache::forever('total_benefit_cost:'.$team->id, $total);

        return $total;
    }
}
