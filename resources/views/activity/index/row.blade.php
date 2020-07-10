<tr>
    <td>{{ $activity->name }}</td>
    <td>
        <a href="{{ route('activity.destroy', compact('activity')) }}" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        <a href="{{ route('activity.edit', compact('activity')) }}" class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
    </td>
</tr>
