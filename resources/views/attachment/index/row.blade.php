<tr>
    <td>{{ $attachment->name }}</td>
    <td>
        <a href="{{ route('attachment.destroy', compact('attachment')) }}" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        <a href="{{ route('attachment.edit', compact('attachment')) }}" class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
    </td>
</tr>
