@extends('layouts.app')

@section('title', __('Laporan Personal'))

@section('content')
    <div class="container">
        <form action="{{ route(Route::currentRouteName()) }}" class="d-flex justify-content-end mb-4">
            <div class="input-group">
                <select class="form-control" name="lecturer_id">
                    <option value="">Pilih Dosen (Semua)</option>
                    @foreach($faculties as $faculty)
                        <optgroup label="{{ $faculty->name }}">
                            @foreach($faculty->studies as $study)
                                <optgroup label="{{ $study->name }}">
                                    @foreach($study->lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}" @if(request()->has('lecturer_id') && request()->get('lecturer_id') == $lecturer->id) selected @endif> {{ $lecturer->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </optgroup>
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
                <span class="d-block">{{ __('Laporan Personal') }}</span>
                <form action="{{ route('report.personal') }}" method="get">
                    <input value="export" name="action" type="hidden">
                    <input value="{{ request()->get('year') }}" name="year" type="hidden">
                    <input value="{{ request()->get('lecturer_id') }}" name="lecturer_id" type="hidden">
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
                            <th scope="col">{{ __('Kegiatan') }}</th>
                            <th scope="col">{{ __('Biaya') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $submission['name'] }}</td>
                                <td style="width:150px" class="text-right">Rp {{ number_format($submission['cost'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="3">Tidak ada pengajuan</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th scope="col" colspan="2" class="text-right">{{ __('Biaya') }}</th>
                            <th class="text-right">Rp {{ number_format(collect($submissions)->sum('cost'), 0, ',', '.') }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
