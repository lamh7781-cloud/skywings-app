<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FlightResource;
use App\Models\Flight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class FlightSearchController extends Controller
{
    public function featured(): AnonymousResourceCollection
    {
        return FlightResource::collection(
            Flight::query()
                ->where('departure_time', '>=', now())
                ->where('seats_available', '>', 0)
                ->orderBy('price')
                ->orderBy('departure_time')
                ->limit(6)
                ->get(),
        );
    }

    public function __invoke(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'origin' => ['required', 'string', 'max:191'],
            'destination' => ['required', 'string', 'max:191', 'different:origin'],
            'departure_date' => ['required', 'date_format:Y-m-d'],
            'return_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:departure_date'],
            'passengers' => ['required', 'integer', 'between:1,9'],
            'cabin_class' => ['required', Rule::in(Flight::CABIN_CLASSES)],
        ]);

        $outbound = $this->matchingFlights(
            $filters['origin'],
            $filters['destination'],
            $filters['departure_date'],
            (int) $filters['passengers'],
            $filters['cabin_class'],
        );

        $return = isset($filters['return_date'])
            ? $this->matchingFlights(
                $filters['destination'],
                $filters['origin'],
                $filters['return_date'],
                (int) $filters['passengers'],
                $filters['cabin_class'],
            )
            : collect();

        return response()->json([
            'data' => [
                'outbound' => FlightResource::collection($outbound)->resolve($request),
                'return' => FlightResource::collection($return)->resolve($request),
            ],
            'meta' => [
                'origin' => $filters['origin'],
                'destination' => $filters['destination'],
                'departure_date' => $filters['departure_date'],
                'return_date' => $filters['return_date'] ?? null,
                'passengers' => (int) $filters['passengers'],
                'cabin_class' => $filters['cabin_class'],
            ],
        ]);
    }

    /**
     * @return Collection<int, Flight>
     */
    private function matchingFlights(
        string $origin,
        string $destination,
        string $date,
        int $passengers,
        string $cabinClass,
    ): Collection {
        return Flight::query()
            ->where('origin', 'like', "%{$origin}%")
            ->where('destination', 'like', "%{$destination}%")
            ->whereDate('departure_time', $date)
            ->where('cabin_class', $cabinClass)
            ->where('seats_available', '>=', $passengers)
            ->orderBy('departure_time')
            ->orderBy('flight_number')
            ->get();
    }
}
