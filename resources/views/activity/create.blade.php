@extends('layouts.app')

@section('title', __('Tambah Kegiatan'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between mb-4">
                    <a class="btn btn-outline-secondary" href="{{ route('activity.index') }}">&laquo; {{ __('Kembali') }}</a>
                </div>
                <div class="card">
                    <div class="card-header bg-umy">{{ __('Detail Kegiatan') }}</div>
                    <div class="card-body">
                        <form action="{{ route('activity.store') }}" method="post">
                            @csrf
                            @method('POST')

                            <div class="form-group">
                                <label for="name">{{ __('Nama') }}</label>
                                <input type="text" name="name" id="name" required class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">{{ __('Tambah') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
