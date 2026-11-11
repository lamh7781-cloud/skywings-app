<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlightResource;
use App\Models\Flight;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AdminFlightController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return FlightResource::collection(
            Flight::query()
                ->orderBy('departure_time')
                ->orderBy('flight_number')
                ->paginate(20),
        );
    }

    public function store(Request $request): JsonResponse
    {
        $flight = Flight::create($request->validate($this->rules($request)));

        return FlightResource::make($flight)->response()->setStatusCode(201);
    }

    public function show(Flight $flight): FlightResource
    {
        return FlightResource::make($flight);
    }

    public function update(Request $request, Flight $flight): FlightResource
    {
        $flight->update($request->validate($this->rules($request, $flight)));

        return FlightResource::make($flight);
    }

    public function destroy(Flight $flight): Response
    {
        $flight->delete();

        return response()->noContent();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(Request $request, ?Flight $flight = null): array
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
