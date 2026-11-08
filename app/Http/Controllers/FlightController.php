<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'departure' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'flight_date' => ['nullable', 'date'],
        ]);

        $flights = Flight::query()
            ->when($filters['departure'] ?? null, fn (Builder $query, string $value) => $query->where('departure', 'like', "%{$value}%"))
            ->when($filters['destination'] ?? null, fn (Builder $query, string $value) => $query->where('destination', 'like', "%{$value}%"))
            ->when($filters['flight_date'] ?? null, fn (Builder $query, string $value) => $query->whereDate('flight_date', $value))
            ->orderBy('flight_date')
            ->orderBy('flight_name')
            ->paginate(10)
            ->withQueryString();

        return view('flights.index', compact('flights'));
    }

    public function create(): View
    {
        return view('flights.form', ['flight' => new Flight]);
    }

    public function store(Request $request): RedirectResponse
    {
        $flight = Flight::create($request->validate($this->validationRules()));

        return redirect()->route('flights.show', $flight)->with('status', 'Đã thêm chuyến bay.');
    }

    public function show(Flight $flight): View
    {
        return view('flights.show', compact('flight'));
    }

    public function edit(Flight $flight): View
    {
        return view('flights.form', compact('flight'));
    }

    public function update(Request $request, Flight $flight): RedirectResponse
    {
        $flight->update($request->validate($this->validationRules()));

        return redirect()->route('flights.show', $flight)->with('status', 'Đã cập nhật chuyến bay.');
    }

    public function destroy(Flight $flight): RedirectResponse
    {
        $flight->delete();

        return redirect()->route('flights.index')->with('status', 'Đã xóa chuyến bay.');
    }

    private function validationRules(): array
    {
        return [
            'flight_name' => ['required', 'string', 'max:255'],
            'departure' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'flight_date' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }
}
