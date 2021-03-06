@extends('layouts.app')

@section('title', __('Ubah Program Studi'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between mb-4">
                    <a class="btn btn-outline-secondary" href="{{ route('study.index') }}">&laquo; {{ __('Kembali') }}</a>
                    <a class="btn btn-outline-primary" href="{{ route('study.create') }}">&plus; {{ __('Tambah Baru') }}</a>
                </div>
                <div class="card">
                    <div class="card-header bg-umy">{{ __('Detail Program Studi') }}</div>
                    <div class="card-body">
                        <form action="{{ route('study.update', compact('study')) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">{{ __('Nama') }}</label>
                                <input type="text" name="name" id="name" required class="form-control @error('name') is-invalid @enderror" value="{{ $study->name }}">
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="faculty_id">{{ __('Fakultas') }}</label>
                                <select name="faculty_id" id="faculty_id" class="form-control @error('faculty_id') is-invalid @enderror" required>
                                    @foreach(\App\Faculty::all() as $faculty)
                                        <option value="{{ $faculty->id }}" @if($study->faculty_id == $faculty->id) selected @endif>{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="#" data-toggle="modal" data-target="#delete" class="btn btn-danger mr-2">{{ __('Hapus') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Ubah') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('body')
    <x-modal type="form"
             id="delete"
             method="delete"
             :action="route('study.destroy', compact('study'))"
             :title="__('Konfirmasi Penghapusan')"
             classes="modal-dialog-centered modal-dialog-scrollable"
             :message="__('Ingin menghapus program studi ini?')">
    </x-modal>
@endpush
