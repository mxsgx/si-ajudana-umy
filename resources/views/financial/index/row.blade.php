<tr>
    <td>{{ $financial->name }}</td>
    <td>
        <a href="{{ route('financial.destroy', compact('financial')) }}" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        <a href="{{ route('financial.edit', compact('financial')) }}" class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
    </td>
</tr>
