@extends('layouts.app')

@section('title', __('Laporan Kegiatan'))

@section('content')
    <div class="container">
        <form action="{{ route(Route::currentRouteName()) }}" class="d-flex justify-content-end mb-4">
            <div class="input-group">
                <select name="activity_id" class="form-control">
                    <option value="">Pilih Kegiatan (Semua)</option>
                    @foreach($activities as $activity)
                        <option value="{{ $activity->id }}" @if(request()->get('activity_id') == $activity->id) selected @endif>{{ $activity->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <select name="year" class="form-control">
                        <option value="">Pilih Tahun (Semua)</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}"
                                    @if(request()->has('year') && request()->get('year') == $year) selected @endif>{{ $year }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-control">
                        <option value="">Pilih Status (Semua)</option>
                        @foreach(\App\Submission::getModel()->statuses as $statusName => $status)
                            <option value="{{ $statusName }}"
                                    @if(request()->has('status') && request()->get('status') == $statusName) selected @endif>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-success" type="submit">{{ __('Proses') }}</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header bg-umy d-flex flex-row justify-content-between align-items-center align-middle">
                <span class="d-block">{{ __('Laporan Kegiatan') }}</span>
                <form action="{{ route('report.activity') }}" method="get">
                    <input value="export" name="action" type="hidden">
                    <input value="{{ request()->get('year') }}" name="year" type="hidden">
                    <input value="{{ request()->get('activity_id') }}" name="activity_id" type="hidden">
                    <input value="{{ request()->get('status') }}" name="status" type="hidden">
                    <button class="btn btn-outline-light">{{ __('Excel') }}</button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive py-2">
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">{{ __('No') }}</th>
                            <th scope="col">{{ __('Nama') }}</th>
                            <th scope="col">{{ __('Judul') }}</th>
                            <th scope="col">{{ __('Prodi') }}</th>
                            <th scope="col">{{ __('Kegiatan') }}</th>
                            <th scope="col">{{ __('Anggaran') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $submission['lecturer'] }}</td>
                                <td>{{ $submission['title'] }}</td>
                                <td>{{ $submission['study'] }}</td>
                                <td>{{ $submission['name'] }}</td>
                                <td style="width:150px" class="text-right">
                                    Rp {{ number_format($submission['cost'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">Tidak ada pengajuan</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th scope="col" colspan="5" class="text-right">{{ __('Biaya') }}</th>
                            <th class="text-right">
                                Rp {{ number_format(collect($submissions)->sum('cost'), 0, ',', '.') }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
