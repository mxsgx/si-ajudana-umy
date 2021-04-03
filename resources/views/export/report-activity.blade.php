<table>
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
            <td data-format="Rp #,##0_-">{{ $submission['cost'] }}</td>
        </tr>
    @empty
        <tr>
            <td class="text-center" colspan="6">Tidak ada pengajuan</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="5">{{ __('Biaya') }}</th>
        <th data-format="Rp #,##0_-">{{ collect($submissions)->sum('cost') }}</th>
    </tr>
    </tfoot>
</table>
