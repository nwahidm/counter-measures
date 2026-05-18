@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container mt-1">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 mb-3">
            <div class="login-brand">
                <img src="{{ asset('assets/image/logo-csr.png') }}"
                    alt="logo" width="100" class="shadow-light rounded-circle p-2 mb-5">
                    <h4 style="color: #00a891;">Unit Audit Internal</h4>
            </div>
            @include('components.notification')
            <div class="card card-primary">
                <div class="card-header">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="nama">{{ __('Nama') }}</label>
                            <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror"
                                name="nama" value="{{ old('nama') }}">
                            @error('nama')
                            <span class=" invalid-feedback" role="alert">
                            {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nik">{{ __('NIK') }}</label>
                            <input id="nik" type="text" class="form-control @error('nik') is-invalid @enderror"
                                name="nik" value="{{ old('nik') }}">
                            @error('nik')
                            <span class=" invalid-feedback" role="alert">
                            {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="jabatan">{{ __('Jabatan') }}</label>
                            <input id="jabatan" type="text" class="form-control @error('jabatan') is-invalid @enderror"
                                name="jabatan" value="{{ old('jabatan') }}">
                            @error('jabatan')
                            <span class=" invalid-feedback" role="alert">
                            {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="alamat">{{ __('Alamat') }}</label>
                            <input id="alamat" type="text" class="form-control @error('alamat') is-invalid @enderror"
                                name="alamat" value="{{ old('alamat') }}">
                            @error('alamat')
                            <span class=" invalid-feedback" role="alert">
                            {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ktp">{{ __('Foto KTP') }}</label>
                            <input id="ktp" type="file" class="form-control @error('ktp') is-invalid @enderror"
                                name="ktp" value="{{ old('ktp') }}" accept="image/*">
                            @error('ktp')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-3 text-muted text-center">
                Sudah punya akun? <a href="{{ route('login') }}">{{ __('Masuk') }}</a>
            </div>
            <div class="simple-footer">
                Copyright © {{ config('app.name') }}. All Rights Reserved
            </div>
        </div>
    </div>
</div>
@endsection