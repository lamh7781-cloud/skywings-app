@extends('layouts.app')

@section('title', $flight->exists ? 'Sửa chuyến bay - SkyWings' : 'Thêm chuyến bay - SkyWings')
@section('body-class', 'bg-light')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="mx-auto" style="max-width: 760px">
                <h1 class="h2 fw-bold mb-4">{{ $flight->exists ? 'Cập nhật chuyến bay' : 'Thêm chuyến bay' }}</h1>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ $flight->exists ? route('flights.update', $flight) : route('flights.store') }}" method="post">
                            @csrf
                            @if ($flight->exists)
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label" for="flight_name">Mã chuyến bay / Hãng bay</label>
                                <input class="form-control @error('flight_name') is-invalid @enderror" id="flight_name" name="flight_name" value="{{ old('flight_name', $flight->flight_name) }}" required maxlength="255">
                                @error('flight_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="departure">Điểm đi</label>
                                    <input class="form-control @error('departure') is-invalid @enderror" id="departure" name="departure" value="{{ old('departure', $flight->departure) }}" required maxlength="255">
                                    @error('departure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="destination">Điểm đến</label>
                                    <input class="form-control @error('destination') is-invalid @enderror" id="destination" name="destination" value="{{ old('destination', $flight->destination) }}" required maxlength="255">
                                    @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="flight_date">Ngày bay</label>
                                    <input class="form-control @error('flight_date') is-invalid @enderror" id="flight_date" name="flight_date" type="date" value="{{ old('flight_date', $flight->flight_date?->format('Y-m-d')) }}" required>
                                    @error('flight_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="price">Giá vé (VND)</label>
                                    <input class="form-control @error('price') is-invalid @enderror" id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $flight->price) }}" required>
                                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-4">
                                <button class="btn btn-primary btn-skywings px-4" type="submit">Lưu chuyến bay</button>
                                <a class="btn btn-outline-secondary px-4" href="{{ route('flights.index') }}">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
