@extends('layouts.app')

@section('title', __('Pengajuan'))

@section('content')
    <div class="container">
        <form action="{{ route(Route::currentRouteName()) }}" class="d-flex justify-content-end mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="{{ __('Nama pengajuan') }}" name="cari">
                <div class="input-group-append" id="button-addon4">
                    <button class="btn btn-outline-success" type="submit">{{ __('Cari') }}</button>
                    <a class="btn btn-success" href="{{ route('submission.create') }}">{{ __('Tambah Pengajuan') }}</a>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-header bg-umy">{{ __('Daftar Pengajuan') }}</div>
            <div class="card-body">
                <div class="table-responsive py-2">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('No') }}</th>
                                <th scope="col">{{ __('Nama Kegiatan') }}</th>
                                <th scope="col">{{ __('Dosen') }}</th>
                                <th scope="col">{{ __('Judul') }}</th>
                                <th scope="col">{{ __('Tanggal') }}</th>
                                <th scope="col">{{ __('Kategori') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                                @include('submission.index.row')
                            @empty
                                @include('submission.index.empty')
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($submissions->lastPage() > 1)
                    <div class="d-flex justify-content-center">
                        {{ $submissions->links() }}
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
        :message="__('Ingin menghapus pengajuan ini?')">
    </x-modal>
@endpush
