<tr>
    <td>{{ $submission->title }}</td>
    <td>
        @can('delete', $submission)
            <a href="{{ route('submission.destroy', compact('submission')) }}" class="btn btn-sm btn-danger m-1"
               data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        @endcan
        @can('update', $submission)
            <a href="{{ route('submission.edit', compact('submission')) }}"
               class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
        @endcan
        <a href="{{ route('submission.show', compact('submission')) }}" class="btn btn-sm btn-info m-1">{{ __('Lihat') }}</a>
        <a href="{{ route('submission.pdf', compact('submission')) }}" class="btn btn-sm btn-secondary m-1">{{ __('PDF') }}</a>
    </td>
</tr>
