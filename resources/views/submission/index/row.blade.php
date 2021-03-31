<tr>
    <td>{{ ($submissions->total() - $loop->index) - (($submissions->currentPage() - 1) * $submissions->perPage() ) }}</td>
    <td>{{ $submission->activity->name }}</td>
    <td>{{ $submission->lecturer->name }}</td>
    <td>{{ $submission->title }}</td>
    <td>{{ optional($submission->date_start)->format('d-m-Y') }}
        - {{ optional($submission->date_end)->format('d-m-Y') }}</td>
    <td>{{ optional($submission->category)->name }}</td>
    <td>{{ $submission->statuses[$submission->status] }}</td>
    <td>
        <p class="m-0">
            @can('delete', $submission)
                <a href="{{ route('submission.destroy', compact('submission')) }}" class="btn btn-sm btn-danger m-1"
                    data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
            @endcan
            @can('update', $submission)
                <a href="{{ route('submission.edit', compact('submission')) }}"
                    class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
            @endcan
        </p>
        <a href="{{ route('submission.pdf', compact('submission')) }}" class="btn btn-sm btn-secondary m-1">{{ __('PDF') }}</a>
        <a href="{{ route('submission.show', compact('submission')) }}" class="btn btn-sm btn-info m-1">{{ __('Lihat') }}</a>
    </td>
</tr>
