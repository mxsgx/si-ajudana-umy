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
            {{ $calc->flatten()->map(function ($data) { return ['amount' => $data]; })->sum('amount') ?? 0 }}
        </th>
    </tr>
    </tfoot>
</table>
