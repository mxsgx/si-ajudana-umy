<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog {{ $classes }}">
        @if($type == 'form')
            <form class="modal-content" action="{{ $action ?? '#' }}" method="post">
                @else
                    <div class="modal-content">
                        @endif
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $title ?? __('Konfirmasi') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @method($method ?? 'POST')
                            @csrf
                            {{ $message ?? __('Anda yakin ingin melanjutkan?') }}
                        </div>
                        <div class="modal-footer">
                            @if($type === 'form')
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ __('Batal') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('Ya') }}</button>
                            @else
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{ __('Batal') }}</button>
                                <button type="button" class="btn btn-primary">{{ __('Ya') }}</button>
                            @endif
                        </div>
                    @if($type == 'form') </form> @else </div> @endif
</div>
</div>
