@extends('layouts.app')

@section('title', __('Ubah Dosen'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between mb-4">
                    <a class="btn btn-outline-secondary" href="{{ route('lecturer.index') }}">&laquo; {{ __('Kembali') }}</a>
                    <a class="btn btn-outline-primary" href="{{ route('lecturer.create') }}">&plus; {{ __('Tambah Baru') }}</a>
                </div>
                <div class="card">
                    <div class="card-header bg-umy">{{ __('Detail Dosen') }}</div>
                    <div class="card-body">
                        <form action="{{ route('lecturer.update', compact('lecturer')) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">{{ __('Nama') }}</label>
                                <input type="text" name="name" id="name" required class="form-control @error('name') is-invalid @enderror" value="{{ $lecturer->name }}">
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nip">{{ __('NIP') }}</label>
                                <input type="number" name="nip" id="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ $lecturer->nip }}">
                                @error('nip')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="nik">{{ __('NIK') }}</label>
                                <input type="number" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ $lecturer->nik }}">
                                @error('nik')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="birth_place">{{ __('Tempat Lahir') }}</label>
                                <input type="text" name="birth_place" id="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ $lecturer->birth_place }}">
                                @error('birth_place')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="birth_date">{{ __('Tanggal Lahir') }}</label>
                                <input type="date" name="birth_date" required id="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ $lecturer->birth_date->format('Y-m-d') }}">
                                @error('birth_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="study_id">{{ __('Prodi') }}</label>
                                <select name="study_id" id="study_id" class="form-control @error('study_id') is-invalid @enderror">
                                    @foreach(\App\Study::all() as $study)
                                        <option value="{{ $study->id }}" @if($lecturer->study_id == $study->id) selected @endif>{{ $study->name }}</option>
                                    @endforeach
                                </select>
                                @error('study_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">{{ __('Alamat') }}</label>
                                <textarea name="address" id="address" rows="5" class="form-control @error('address') is-invalid @enderror">{{ $lecturer->address }}</textarea>
                                @error('address')
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
             :action="route('lecturer.destroy', compact('lecturer'))"
             :title="__('Konfirmasi Penghapusan')"
             classes="modal-dialog-centered modal-dialog-scrollable"
             :message="__('Ingin menghapus dosen ini? Pengguna yang berkaitan dengan dosen ini akan ikut terhapus!')">
    </x-modal>
@endpush
