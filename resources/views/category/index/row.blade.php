<tr>
    <td>{{ $category->name }}</td>
    <td>
        <a href="{{ route('category.destroy', compact('category')) }}" class="btn btn-sm btn-danger m-1" data-toggle="modal" data-target="#delete">{{ __('Hapus') }}</a>
        <a href="{{ route('category.edit', compact('category')) }}" class="btn btn-sm btn-success m-1">{{ __('Sunting') }}</a>
    </td>
</tr>
