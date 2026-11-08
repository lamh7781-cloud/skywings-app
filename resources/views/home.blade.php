@extends('layouts.app')

@section('title', 'SkyWings - Tìm chuyến bay')
@section('description', 'Tìm và đặt chuyến bay dễ dàng cùng SkyWings.')

@section('content')
    <section class="hero d-flex align-items-center" aria-labelledby="hero-title">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-9">
                    <p class="hero-kicker mb-3">Mở lối hành trình của bạn</p>
                    <h1 class="hero-title mb-3" id="hero-title">Bay đến nơi bạn luôn muốn đến</h1>
                    <p class="text-secondary fs-5 mb-4">Tìm chuyến bay phù hợp và bắt đầu chuyến đi tiếp theo cùng SkyWings.</p>
                </div>
            </div>

            <div class="card search-card bg-white mt-4" id="tim-chuyen-bay">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 fw-bold mb-4">Tìm chuyến bay</h2>
                    <form action="{{ route('flights.index') }}" method="get">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-6 col-xl-3">
                                <label class="form-label" for="departure">Điểm đi</label>
                                <input class="form-control" id="departure" name="departure" type="text" placeholder="Ví dụ: Hà Nội (HAN)" autocomplete="off">
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <label class="form-label" for="destination">Điểm đến</label>
                                <input class="form-control" id="destination" name="destination" type="text" placeholder="Ví dụ: Đà Nẵng (DAD)" autocomplete="off">
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <label class="form-label" for="flight_date">Ngày đi</label>
                                <input class="form-control" id="flight_date" name="flight_date" type="date">
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <button class="btn btn-primary btn-skywings w-100" type="submit">Tìm chuyến bay</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
