<tr>
    <td>{{ ($lecturers->currentPage() - 1) * $lecturers->perPage() + $loop->index + 1 }}</td>
    <td>{{ ($lecturer->nip ?? $lecturer->nik) ?? '-' }}</td>
    <td>{{ $lecturer->name }}</td>
    <td>
        <a href="{{ route('lecturer.destroy', compact('lecturer')) }}" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        <a href="{{ route('lecturer.edit', compact('lecturer')) }}" class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
    </td>
</tr>
