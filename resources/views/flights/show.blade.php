@extends('layouts.app')

@section('title', $flight->flight_name.' - SkyWings')
@section('body-class', 'bg-light')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="mx-auto" style="max-width: 760px">
                <p class="text-primary fw-semibold mb-2">CHI TIẾT CHUYẾN BAY</p>
                <h1 class="h2 fw-bold mb-4">{{ $flight->flight_name }}</h1>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Điểm đi</dt><dd class="col-sm-8">{{ $flight->departure }}</dd>
                            <dt class="col-sm-4">Điểm đến</dt><dd class="col-sm-8">{{ $flight->destination }}</dd>
                            <dt class="col-sm-4">Ngày bay</dt><dd class="col-sm-8">{{ $flight->flight_date->format('d/m/Y') }}</dd>
                            <dt class="col-sm-4">Giá vé</dt><dd class="col-sm-8">{{ number_format((float) $flight->price, 0, ',', '.') }}đ</dd>
                        </dl>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a class="btn btn-primary" href="{{ route('bookings.show', $flight) }}">Đặt vé</a>
                            <a class="btn btn-outline-primary" href="{{ route('flights.edit', $flight) }}">Sửa chuyến bay</a>
                            <a class="btn btn-outline-secondary" href="{{ route('flights.index') }}">Quay lại danh sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
