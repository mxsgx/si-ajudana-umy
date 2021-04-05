@extends('layouts.app')

@section('title', __('Laporan Rekap Aktivitas'))

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
                <span class="d-block">{{ __('Laporan Rekap Aktivitas') }}</span>
                <form action="{{ route('report.recap.activity') }}" method="get">
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
                            <th scope="col" class="text-center">{{ __('No') }}</th>
                            <th scope="col">{{ __('Kegiatan') }}</th>
                            <th scope="col" class="text-center">{{ __('TS') }}</th>
                            <th scope="col" class="text-center">{{ __('TE') }}</th>
                            <th scope="col" class="text-center">{{ __('TM') }}</th>
                            <th scope="col" class="text-center">{{ __('TI') }}</th>
                            <th scope="col" class="text-center">{{ __('PPI') }}</th>
                            <th scope="col" class="text-center">{{ __('Total') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $activity['name'] }}</td>
                                <td class="text-center">
                                    {{ number_format(isset($activity['submissions']['Teknik Sipil']) ? collect($activity['submissions']['Teknik Sipil'])->count() : 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{ number_format(isset($activity['submissions']['Teknik Elektro']) ? collect($activity['submissions']['Teknik Elektro'])->count() : 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{ number_format(isset($activity['submissions']['Teknik Mesin']) ? collect($activity['submissions']['Teknik Mesin'])->count() : 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{ number_format(isset($activity['submissions']['Teknologi Informasi']) ? collect($activity['submissions']['Teknologi Informasi'])->count() : 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{ number_format(isset($activity['submissions']['Program Profesi Insinyur']) ? collect($activity['submissions']['Program Profesi Insinyur'])->count() : 0, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    {{ number_format(collect($activity['submissions'])->map(function ($data) { return ['total' => collect($data)->count()]; })->sum('total')) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">Tidak ada rekap</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th scope="col" colspan="2" class="text-center">{{ __('Jumlah') }}</th>
                            <th class="text-center">
                                {{ $calc->sum('Teknik Sipil') ?? 0 }}
                            </th>
                            <th class="text-center">
                                {{ $calc->sum('Teknik Elektro') ?? 0 }}
                            </th>
                            <th class="text-center">
                                {{ $calc->sum('Teknik Mesin') ?? 0 }}
                            </th>
                            <th class="text-center">
                                {{ $calc->sum('Teknologi Informasi') ?? 0 }}
                            </th>
                            <th class="text-center">
                                {{ $calc->sum('Program Profesi Insinyur') ?? 0 }}
                            </th>
                            <th class="text-center">
                                {{ optional($calc->flatten()->map(function ($data) { return ['amount' => $data]; }))->sum('amount') ?? 0 }}
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
