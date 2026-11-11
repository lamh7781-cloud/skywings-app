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
                                <label class="form-label" for="flight_number">Mã chuyến bay</label>
                                <input class="form-control @error('flight_number') is-invalid @enderror" id="flight_number" name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}" required maxlength="255">
                                @error('flight_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="origin">Điểm đi</label>
                                    <input class="form-control @error('origin') is-invalid @enderror" id="origin" name="origin" value="{{ old('origin', $flight->origin) }}" required maxlength="255">
                                    @error('origin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="destination">Điểm đến</label>
                                    <input class="form-control @error('destination') is-invalid @enderror" id="destination" name="destination" value="{{ old('destination', $flight->destination) }}" required maxlength="255">
                                    @error('destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="departure_time">Ngày và giờ khởi hành</label>
                                    <input class="form-control @error('departure_time') is-invalid @enderror" id="departure_time" name="departure_time" type="datetime-local" value="{{ old('departure_time', $flight->departure_time?->format('Y-m-d\TH:i')) }}" required>
                                    @error('departure_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="price">Giá vé (VND)</label>
                                    <input class="form-control @error('price') is-invalid @enderror" id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $flight->price) }}" required>
                                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="cabin_class">Hạng ghế</label>
                                    <select class="form-select @error('cabin_class') is-invalid @enderror" id="cabin_class" name="cabin_class" required>
                                        @foreach (['economy' => 'Phổ thông', 'premium_economy' => 'Phổ thông đặc biệt', 'business' => 'Thương gia', 'first' => 'Hạng nhất'] as $value => $label)
                                            <option value="{{ $value }}" @selected(old('cabin_class', $flight->cabin_class ?? 'economy') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('cabin_class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="seats_available">Số ghế còn</label>
                                    <input class="form-control @error('seats_available') is-invalid @enderror" id="seats_available" name="seats_available" type="number" min="0" max="65535" value="{{ old('seats_available', $flight->seats_available ?? 100) }}" required>
                                    @error('seats_available')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
