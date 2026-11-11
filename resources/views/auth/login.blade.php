@extends('layouts.app')

@section('title', 'Đăng nhập - SkyWings')
@section('body-class', 'bg-light')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="mx-auto" style="max-width: 480px">
                <h1 class="h2 fw-bold mb-4">Đăng nhập quản trị</h1>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('login.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password">Mật khẩu</label>
                                <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
                            </div>
                            <label class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" value="1">
                                <span class="form-check-label">Ghi nhớ đăng nhập</span>
                            </label>
                            <button class="btn btn-primary btn-skywings" type="submit">Đăng nhập</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
