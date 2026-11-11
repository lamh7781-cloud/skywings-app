<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FlightController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'origin' => ['nullable', 'string', 'max:191'],
            'destination' => ['nullable', 'string', 'max:191'],
            'departure_date' => ['nullable', 'date'],
            'passengers' => ['nullable', 'integer', 'between:1,9'],
            'cabin_class' => ['nullable', Rule::in(Flight::CABIN_CLASSES)],
        ]);

        $flights = Flight::query()
            ->when($filters['origin'] ?? null, fn (EloquentBuilder $query, string $value) => $query->where('origin', 'like', "%{$value}%"))
            ->when($filters['destination'] ?? null, fn (EloquentBuilder $query, string $value) => $query->where('destination', 'like', "%{$value}%"))
            ->when($filters['departure_date'] ?? null, fn (EloquentBuilder $query, string $value) => $query->whereDate('departure_time', $value))
            ->when($filters['passengers'] ?? null, fn (EloquentBuilder $query, int $value) => $query->where('seats_available', '>=', $value))
            ->when($filters['cabin_class'] ?? null, fn (EloquentBuilder $query, string $value) => $query->where('cabin_class', $value))
            ->orderBy('departure_time')
            ->orderBy('flight_number')
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
        $flight = Flight::create($request->validate($this->validationRules($request)));

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
        $flight->update($request->validate($this->validationRules($request, $flight)));

        return redirect()->route('flights.show', $flight)->with('status', 'Đã cập nhật chuyến bay.');
    }

    public function destroy(Flight $flight): RedirectResponse
    {
        $flight->delete();

        return redirect()->route('flights.index')->with('status', 'Đã xóa chuyến bay.');
    }

    private function validationRules(Request $request, ?Flight $flight = null): array
    {
        $cabinClass = (string) $request->input('cabin_class', '');

        return [
            'flight_number' => [
                'required',
                'string',
                'max:191',
                Rule::unique('flights', 'flight_number')
                    ->where(fn (Builder $query) => $query->where('cabin_class', $cabinClass))
                    ->ignore($flight),
            ],
            'origin' => ['required', 'string', 'max:191'],
            'destination' => ['required', 'string', 'max:191', 'different:origin'],
            'departure_time' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'cabin_class' => ['required', Rule::in(Flight::CABIN_CLASSES)],
            'seats_available' => ['required', 'integer', 'between:0,65535'],
        ];
    }
}
