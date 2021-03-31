<table>
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
            <td data-format="Rp #,##0_-">{{ $submission['cost'] ?? 0 }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3">Tidak ada pengajuan</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="2">{{ __('Biaya') }}</th>
        <th data-format="Rp #,###_-">{{ collect($submissions)->sum('cost') }}</th>
    </tr>
    </tfoot>
</table>
