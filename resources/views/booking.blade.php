@extends('layouts.app')

@section('title', 'Đặt vé - SkyWings')
@section('body-class', 'bg-light')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="mx-auto" style="max-width: 760px">
                <p class="text-primary fw-semibold mb-2">BƯỚC 2 / 2</p>
                <h1 class="h2 fw-bold mb-4">Thông tin chuyến bay</h1>
                <div class="card search-card border-0">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold mb-3">{{ $flight->flight_name }}</h2>
                        <p class="mb-1"><strong>Hành trình:</strong> {{ $flight->departure }} → {{ $flight->destination }}</p>
                        <p class="mb-1"><strong>Ngày bay:</strong> {{ $flight->flight_date->format('d/m/Y') }}</p>
                        <p class="mb-0"><strong>Giá vé:</strong> {{ number_format((float) $flight->price, 0, ',', '.') }}đ</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h5 fw-bold mb-4">Thông tin hành khách</h2>
                        <form id="booking-form">
                            <div class="mb-3">
                                <label class="form-label" for="full-name">Họ và tên</label>
                                <input class="form-control" id="full-name" name="full_name" type="text" autocomplete="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" autocomplete="email" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="phone">Số điện thoại</label>
                                <input class="form-control" id="phone" name="phone" type="tel" autocomplete="tel" required>
                            </div>
                            <p class="small text-secondary">Giao diện demo: thông tin không được lưu và chưa có thanh toán hoặc đặt chỗ thực tế.</p>
                            <button class="btn btn-primary btn-skywings" type="submit">Xác nhận đặt vé</button>
                            <div class="alert alert-info mt-3 mb-0 d-none" id="booking-message" role="status" aria-live="polite"></div>
                        </form>
                    </div>
                </div>
                <a class="btn btn-outline-secondary mt-3" href="{{ route('flights.index') }}">Quay lại danh sách chuyến bay</a>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('booking-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const message = document.getElementById('booking-message');
            message.textContent = 'Đây là giao diện demo; không có thông tin hoặc đặt chỗ nào được lưu.';
            message.classList.remove('d-none');
        });
    </script>
@endsection
