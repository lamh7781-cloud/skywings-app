@extends('layouts.app')

@section('title', 'Chuyến bay - SkyWings')
@section('body-class', 'bg-light')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <p class="text-primary fw-semibold mb-2">CÁC CHUYẾN BAY</p>
                    <h1 class="h2 fw-bold mb-0">Chọn chuyến bay phù hợp</h1>
                </div>
                <a class="btn btn-primary btn-skywings rounded-pill px-4" href="{{ route('flights.create') }}">Thêm chuyến bay</a>
            </div>

            <form class="card border-0 shadow-sm mb-4" action="{{ route('flights.index') }}" method="get">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label" for="departure">Điểm đi</label>
                            <input class="form-control" id="departure" name="departure" value="{{ request('departure') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="destination">Điểm đến</label>
                            <input class="form-control" id="destination" name="destination" value="{{ request('destination') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="flight_date">Ngày đi</label>
                            <input class="form-control" id="flight_date" name="flight_date" type="date" value="{{ request('flight_date') }}">
                        </div>
                        <div class="col-md-1 d-grid">
                            <button class="btn btn-primary" type="submit">Lọc</button>
                        </div>
                    </div>
                </div>
            </form>

            @if ($flights->isEmpty())
                <div class="alert alert-info" role="status">Chưa có chuyến bay phù hợp. Hãy thêm chuyến bay mới.</div>
            @else
                <div class="vstack gap-3">
                    @foreach ($flights as $flight)
                        <article class="card flight-card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-md-3">
                                        <p class="text-secondary small mb-1">{{ $flight->flight_name }}</p>
                                        <p class="fw-bold mb-0">{{ $flight->flight_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="col-6 col-md-2 text-center">
                                        <p class="h5 fw-bold mb-0">{{ $flight->departure }}</p>
                                        <p class="small text-secondary mb-0">Điểm đi</p>
                                    </div>
                                    <div class="col-6 col-md-2 text-center">
                                        <div class="border-top border-2"></div>
                                        <p class="small text-secondary mt-1 mb-0">Bay thẳng</p>
                                    </div>
                                    <div class="col-6 col-md-2 text-center">
                                        <p class="h5 fw-bold mb-0">{{ $flight->destination }}</p>
                                        <p class="small text-secondary mb-0">Điểm đến</p>
                                    </div>
                                    <div class="col-6 col-md-3 text-md-end">
                                        <p class="h5 fw-bold text-primary mb-2">{{ number_format((float) $flight->price, 0, ',', '.') }}đ</p>
                                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('flights.show', $flight) }}">Chi tiết</a>
                                            <a class="btn btn-primary btn-sm" href="{{ route('bookings.show', $flight) }}">Đặt vé</a>
                                            <a class="btn btn-outline-primary btn-sm" href="{{ route('flights.edit', $flight) }}">Sửa</a>
                                            <form action="{{ route('flights.destroy', $flight) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa chuyến bay này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm" type="submit">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-4">{{ $flights->links() }}</div>
            @endif
        </div>
    </section>
@endsection
