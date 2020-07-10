<tr>
    <td>{{ $participant->name }}</td>
    <td>
        <a href="{{ route('participant.destroy', compact('participant')) }}" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        <a href="{{ route('participant.edit', compact('participant')) }}" class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
    </td>
</tr>
