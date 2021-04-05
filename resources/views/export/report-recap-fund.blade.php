<table>
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
            <td data-format="Rp #,##0_-">
                {{ isset($activity['submissions']['Teknik Sipil']) ? collect($activity['submissions']['Teknik Sipil'])->sum('amount') : 0 }}</td>
            <td data-format="Rp #,##0_-">
                {{ isset($activity['submissions']['Teknik Elektro']) ? collect($activity['submissions']['Teknik Elektro'])->sum('amount') : 0 }}</td>
            <td data-format="Rp #,##0_-">
                {{ isset($activity['submissions']['Teknik Mesin']) ? collect($activity['submissions']['Teknik Mesin'])->sum('amount') : 0 }}</td>
            <td data-format="Rp #,##0_-">
                {{ isset($activity['submissions']['Teknologi Informasi']) ? collect($activity['submissions']['Teknologi Informasi'])->sum('amount') : 0 }}</td>
            <td data-format="Rp #,##0_-">
                {{ isset($activity['submissions']['Program Profesi Insinyur']) ? collect($activity['submissions']['Program Profesi Insinyur'])->sum('amount') : 0 }}</td>
            <td data-format="Rp #,##0_-">
                {{ collect($activity['submissions'])->map(function ($data) { return ['amount' => collect($data)->sum('amount')]; })->sum('amount') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8">Tidak ada rekap</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="2">{{ __('Jumlah') }}</th>
        <th data-format="Rp #,##0_-">
            {{ $calc->sum('Teknik Sipil') }}
        </th>
        <th data-format="Rp #,##0_-">
            {{ $calc->sum('Teknik Elektro') }}
        </th>
        <th data-format="Rp #,##0_-">
            {{ $calc->sum('Teknik Mesin') }}
        </th>
        <th data-format="Rp #,##0_-">
            {{ $calc->sum('Teknologi Informasi') }}
        </th>
        <th data-format="Rp #,##0_-">
            {{ $calc->sum('Program Profesi Insinyur') }}
        </th>
        <th data-format="Rp #,##0_-">
            {{ $calc->flatten()->map(function ($data) { return ['amount' => $data]; })->sum('amount') }}
        </th>
    </tr>
    </tfoot>
</table>
