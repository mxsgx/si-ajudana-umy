@extends('layouts.app')

@section('title', __('Dosen'))

@section('content')
    <div class="container">
        <form action="{{ route(Route::currentRouteName()) }}" class="d-flex justify-content-end mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="{{ __('Nama dosen') }}" name="cari">
                <div class="input-group-append" id="button-addon4">
                    <button class="btn btn-outline-success" type="submit">{{ __('Cari') }}</button>
                    <a class="btn btn-success" href="{{ route('lecturer.create') }}">{{ __('Tambah Dosen') }}</a>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-header bg-umy">{{ __('Daftar Dosen') }}</div>
            <div class="card-body">
                <div class="table-responsive py-2">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('NIK/NIP') }}</th>
                                <th scope="col">{{ __('Nama') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lecturers as $lecturer)
                                @include('lecturer.index.row')
                            @empty
                                @include('lecturer.index.empty')
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($lecturers->lastPage() > 1)
                    <div class="d-flex justify-content-center">
                        {{ $lecturers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('body')
    <x-modal type="form"
             id="delete"
             method="delete"
             :title="__('Konfirmasi Penghapusan')"
             classes="modal-dialog-centered modal-dialog-scrollable"
             :message="__('Ingin menghapus dosen ini? Pengguna yang berkaitan dengan dosen ini akan ikut terhapus!')">
    </x-modal>
@endpush
