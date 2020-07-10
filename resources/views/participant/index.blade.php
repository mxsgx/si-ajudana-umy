@extends('layouts.app')

@section('title', __('Peserta'))

@section('content')
    <div class="container">
        <form action="{{ route(Route::currentRouteName()) }}" class="d-flex justify-content-end mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="{{ __('Nama peserta') }}" name="cari">
                <div class="input-group-append" id="button-addon4">
                    <button class="btn btn-outline-success" type="submit">{{ __('Cari') }}</button>
                    <a class="btn btn-success" href="{{ route('participant.create') }}">{{ __('Tambah Peserta') }}</a>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-header bg-umy">{{ __('Daftar Peserta') }}</div>
            <div class="card-body">
                <div class="table-responsive py-2">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Nama') }}</th>
                                <th scope="col">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @each('participant.index.row', $participants, 'participant', 'participant.index.empty')
                        </tbody>
                    </table>
                </div>

                @if($participants->lastPage() > 1)
                    <div class="d-flex justify-content-center">
                        {{ $participants->links() }}
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
             :message="__('Ingin menghapus peserta ini?')">
    </x-modal>
@endpush
