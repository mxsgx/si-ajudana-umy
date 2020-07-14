@extends('layouts.app')

@section('title', $submission->name)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between mb-4">
                    <a class="btn btn-outline-secondary"
                       href="{{ route('submission.index') }}">&laquo; {{ __('Kembali') }}</a>
                </div>
                <div class="card">
                    <div class="card-header bg-umy">{{ __('Detail Pengajuan') }}</div>
                    <div class="card-body">
                        <h3>Detail</h3>
                        <div class="table-responsive py-2">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>{{ __('Nama Dosen') }}</th>
                                    <td>{{ $submission->lecturer->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Nama Kegiatan') }}</th>
                                    <td>{{ $submission->name  }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Status') }}</th>
                                    <td>{{ $submission->statuses[$submission->status]  }}</td>
                                </tr>
                                @if($submission->status === 'rejected')
                                    <tr>
                                        <th>{{ __('Alasan') }}</th>
                                        <td>{{ $submission->note ?? '-' }}</td>
                                    </tr>
                                @endif
                                @if($submission->status !== 'unauthorized')
                                    <tr>
                                        <th>{{ __('Diauthorisasi Oleh') }}</th>
                                        <td>{{ optional($submission->authorized)->name ?? '(Belum Diatur)' }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>{{ __('Tanggal') }}</th>
                                    <td>{{ $submission->readable_datetime }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Tempat Pelaksanaan') }}</th>
                                    <td>{{ $submission->place }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Kategori') }}</th>
                                    <td>{{ $submission->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Kategori Peserta') }}</th>
                                    <td>
                                        {{ $submission->participants->map(function($participant) {return $participant->name;})->join(", ") }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Judul') }}</th>
                                    <td>{{ $submission->title }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Penulis') }}</th>
                                    <td>{{ $submission->writer }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($submission->financials->count())
                            <h3>Rincian Biaya</h3>
                            <div class="table-responsive py-2">
                                <table class="table table-bordered">
                                    <tbody>
                                    @foreach($submission->financials()->withPivot(['amount'])->get() as $financial)
                                        <tr>
                                            <th>{{ $financial->name }}</th>
                                            <td>Rp {{ number_format($financial->pivot->amount, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>{{ __('Total') }}</th>
                                        <td>
                                            Rp {{ number_format($submission->financials()->withPivot(['amount'])->newPivot()->where('submission_id', '=', $submission->id)->sum('amount'), 2, ',', '.') }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($submission->attachments()->get()->count())
                            <h3>Lampiran</h3>
                            <div class="table-responsive py-2">
                                <table class="table table-bordered">
                                    <tbody>
                                    @foreach($submission->attachments()->withPivot(['file_name', 'name'])->get() as $attachment)
                                        @php($attachmentSubmission = $attachment->pivot)
                                        <tr>
                                            <th>{{ $attachment->name }}</th>
                                            <td>
                                                <a href="{{ route('submission.attachment', compact(['submission', 'attachmentSubmission'])) }}"
                                                   target="_blank">{{ $attachment->pivot->file_name }}</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('submission.pdf', compact('submission')) }}"
                               class="btn btn-secondary mr-2">{{ __('PDF') }}</a>
                            @can('update', $submission)
                                <a href="{{ route('submission.edit', compact('submission')) }}"
                                   class="btn btn-primary mr-2">{{ __('Ubah') }}</a>
                            @endcan
                            @if($submission->status === 'unauthorized')
                                @can('authorize', $submission)
                                    <a href="{{ route('submission.authorize', compact('submission')) }}"
                                       data-toggle="modal"
                                       data-target="#authorize"
                                       class="btn btn-primary mr-2">{{ __('Authorisasi') }}</a>
                                @endcan
                            @endif
                            @if($submission->status === 'authorized')
                                @can('approve', $submission)
                                    <a href="{{ route('submission.approve', compact('submission')) }}"
                                       data-toggle="modal"
                                       data-target="#approve"
                                       class="btn btn-success mr-2">{{ __('Setujui') }}</a>
                                @endcan
                                @can('reject', $submission)
                                    <a href="{{ route('submission.reject', compact('submission')) }}"
                                       data-toggle="modal"
                                       data-target="#reject"
                                       class="btn btn-danger mr-2">{{ __('Tolak') }}</a>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('body')
    @if($submission->status === 'unauthorized')
        @can('authorize', $submission)
            <x-modal type="form"
                     id="authorize"
                     method="patch"
                     :action="route('submission.authorize', compact('submission'))"
                     :title="__('Konfirmasi Authorisasi')"
                     classes="modal-dialog-centered modal-dialog-scrollable">
                <x-slot name="message">
                    @if(auth()->user()->role === 'admin')
                        <p>{{ __('Tolak pengajuan ini?') }}</p>
                        <label for="authorized_by" class="d-block">{{ __('Di authorisasi oleh:') }}</label>
                        <select name="authorized_by" id="authorized_by" class="form-control">
                            @foreach(\App\User::where('role', '=', 'head-of-program-study')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <p>{{ __('Authorisasi pengajuan ini?') }}</p>
                    @endif
                </x-slot>
            </x-modal>
        @endcan
    @endif
    @if($submission->status === 'authorized')
        @can('approve', $submission)
            <x-modal type="form"
                     id="approve"
                     method="patch"
                     :action="route('submission.approve', compact('submission'))"
                     :title="__('Konfirmasi Penyetujuan')"
                     classes="modal-dialog-centered modal-dialog-scrollable"
                     :message="__('Setujui pengajuan ini?')">
            </x-modal>
        @endcan
        @can('reject', $submission)
            <x-modal type="form"
                     id="reject"
                     method="patch"
                     :action="route('submission.reject', compact('submission'))"
                     :title="__('Konfirmasi Penolakan')"
                     classes="modal-dialog-centered modal-dialog-scrollable">
                <x-slot name="message">
                    <p>{{ __('Tolak pengajuan ini?') }}</p>
                    <label for="note" class="d-block">{{ __('Beri alasan/catatan:') }}</label>
                    <textarea name="note" id="note" class="form-control"></textarea>
                </x-slot>
            </x-modal>
        @endcan
    @endif
@endpush
