<table>
    <thead>
    <tr>
        <th scope="col">{{ __('No') }}</th>
        <th scope="col">{{ __('Kegiatan') }}</th>
        <th scope="col">{{ __('Tanggal') }}</th>
        <th scope="col">{{ __('Biaya') }}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($submissions as $submission)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $submission['name'] }}</td>
            <td data-format="d-m-y">{{ $submission['date'] }}</td>
            <td data-format="Rp #,##0_-">{{ $submission['cost'] ?? 0 }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">Tidak ada pengajuan</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="3">{{ __('Biaya') }}</th>
        <th data-format="Rp #,###_-">{{ collect($submissions)->sum('cost') }}</th>
    </tr>
    </tfoot>
</table>
