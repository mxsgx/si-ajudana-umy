@extends('layouts.app')

@section('title', __('Laporan Kegiatan'))

@section('content')
    <div class="container">
        <form action="{{ route(Route::currentRouteName()) }}" class="d-flex justify-content-end mb-4">
            <div class="input-group">
                <select name="year" class="form-control">
                    <option value="">Pilih Tahun (Semua)</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}"
                                @if(request()->has('year') && request()->get('year') == $year) selected @endif>{{ $year }}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
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
                <span class="d-block">{{ __('Laporan Rekap') }}</span>
                <form action="{{ route('report.recap') }}" method="get">
                    <input value="export" name="action" type="hidden">
                    <input value="{{ request()->get('year') }}" name="year" type="hidden">
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
                            <th scope="col">{{ __('TS') }}</th>
                            <th scope="col">{{ __('TE') }}</th>
                            <th scope="col">{{ __('TM') }}</th>
                            <th scope="col">{{ __('TI') }}</th>
                            <th scope="col">{{ __('PPI') }}</th>
                            <th scope="col">{{ __('Jumlah') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $activity['name'] }}</td>
                                <td class="text-right">
                                    Rp {{ number_format(isset($activity['submissions']['Teknik Sipil']) ? collect($activity['submissions']['Teknik Sipil'])->sum('amount') : 0, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    Rp {{ number_format(isset($activity['submissions']['Teknik Elektro']) ? collect($activity['submissions']['Teknik Elektro'])->sum('amount') : 0, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    Rp {{ number_format(isset($activity['submissions']['Teknik Mesin']) ? collect($activity['submissions']['Teknik Mesin'])->sum('amount') : 0, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    Rp {{ number_format(isset($activity['submissions']['Teknologi Informasi']) ? collect($activity['submissions']['Teknologi Informasi'])->sum('amount') : 0, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    Rp {{ number_format(isset($activity['submissions']['Program Profesi Insinyur']) ? collect($activity['submissions']['Program Profesi Insinyur'])->sum('amount') : 0, 0, ',', '.') }}</td>
                                <td class="text-right">
                                    Rp {{ number_format(collect($activity['submissions'])->map(function ($data) { return ['amount' => collect($data)->sum('amount')]; })->sum('amount')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">Tidak ada rekap</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th scope="col" colspan="2" class="text-right">{{ __('Jumlah') }}</th>
                            <th class="text-right">
                                Rp {{ number_format($calc->sum('Teknik Sipil'), 0, ',', '.') }}
                            </th>
                            <th class="text-right">
                                Rp {{ number_format($calc->sum('Teknik Elektro'), 0, ',', '.') }}
                            </th>
                            <th class="text-right">
                                Rp {{ number_format($calc->sum('Teknik Mesin'), 0, ',', '.') }}
                            </th>
                            <th class="text-right">
                                Rp {{ number_format($calc->sum('Teknik Informasi'), 0, ',', '.') }}
                            </th>
                            <th class="text-right">
                                Rp {{ number_format($calc->sum('Program Profesi Insinyur'), 0, ',', '.') }}
                            </th>
                            <th class="text-right">
                                Rp {{ number_format($calc->flatten()->map(function ($data) { return ['amount' => $data]; })->sum('amount'), 0, ',', '.') }}
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
