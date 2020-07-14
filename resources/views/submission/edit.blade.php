@extends('layouts.app')

@section('title', __('Ubah Pengajuan'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between mb-4">
                    <a class="btn btn-outline-secondary"
                       href="{{ route('submission.index') }}">&laquo; {{ __('Kembali') }}</a>
                    <a class="btn btn-outline-primary"
                       href="{{ route('submission.create') }}">&plus; {{ __('Tambah Baru') }}</a>
                </div>
                <div class="card" x-data="data()">
                    <div class="card-header bg-umy">{{ __('Detail Pengajuan') }}</div>
                    <div class="card-body">
                        <form action="{{ route('submission.update', compact('submission')) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            @if(auth()->user()->role === 'admin')
                                <div class="form-group">
                                    <label for="lecturer_id">{{ __('Nama Dosen') }}</label>
                                    <select name="lecturer_id" id="lecturer_id" required
                                            class="form-control @error('lecturer_id') is-invalid @enderror">
                                        @foreach(\App\Lecturer::all(['id', 'name']) as $lecturer)
                                            <option value="{{ $lecturer->id }}"
                                                    @if($submission->lecturer_id == $lecturer->id) selected @endif>{{ $lecturer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('lecturer_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">{{ __('Status') }}</label>
                                    <select name="status" id="status" required
                                            @change="statusOnChange($event.target.value)"
                                            class="form-control @error('lecturer_id') is-invalid @enderror">
                                        @foreach(\App\Submission::getModel()->statuses as $status => $name)
                                            <option value="{{ $status }}"
                                                    @if($submission->status == $status) selected @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <template x-if="status !== 'unauthorized'">
                                    <div class="form-group">
                                        <label for="authorized_by">{{ __('Diauthorisasi Oleh:') }}</label>
                                        <select name="authorized_by" id="authorized_by" required
                                                class="form-control @error('lecturer_id') is-invalid @enderror">
                                            @foreach(\App\User::where('role', '=', 'head-of-program-study')->get() as $user)
                                                <option value="{{ $user->id }}"
                                                        @if($submission->authorized_by == $user->id) selected @endif>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </template>
                            @endif

                            <div class="form-group">
                                <label for="activity_id">{{ __('Jenis Kegiatan') }}</label>
                                <select name="activity_id" id="activity_id" required
                                        class="form-control @error('activity_id') is-invalid @enderror">
                                    @foreach(\App\Activity::all(['id', 'name']) as $activity)
                                        <option value="{{ $activity->id }}"
                                                @if($submission->activity_id == $activity->id) selected @endif>{{ $activity->name }}</option>
                                    @endforeach
                                </select>
                                @error('activity_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Nama Kegiatan') }}</label>
                                <input type="text" name="name" id="name" required
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ $submission->name }}">
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="row">
                                        <div class="col-md-7 form-group">
                                            <label for="date_start">{{ __('Tanggal') }}</label>
                                            <input type="date" name="date_start" id="date_start" required
                                                   class="form-control @error('date_start') is-invalid @enderror"
                                                   value="{{ $submission->date_start->format('Y-m-d') }}">
                                            @error('date_start')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label for="time_start">{{ __('Jam') }}</label>
                                            <input type="time" name="time_start" id="time_start"
                                                   class="form-control @error('time_start') is-invalid @enderror"
                                                   value="{{ optional($submission->time_start)->format('H:i') }}">
                                            @error('time_start')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="row">
                                        <div class="col-md-7 form-group">
                                            <label for="date_end">{{ __('Sampai') }}</label>
                                            <input type="date" name="date_end" id="date_end"
                                                   class="form-control @error('date_end') is-invalid @enderror"
                                                   value="{{ optional($submission->date_end)->format('Y-m-d') }}">
                                            @error('date_end')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label for="time_end">{{ __('Waktu') }}</label>
                                            <input type="time" name="time_end" id="time_end"
                                                   class="form-control @error('time_end') is-invalid @enderror"
                                                   value="{{ optional($submission->time_end)->format('H:i') }}">
                                            @error('time_end')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="place">{{ __('Tempat Pelaksanaan') }}</label>
                                <input type="text" name="place" id="place" required
                                       class="form-control @error('place') is-invalid @enderror"
                                       value="{{ $submission->place }}">
                                @error('place')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group card card-body">
                                <label class="d-block">{{ __('Kategori') }}</label>

                                @foreach(\App\Category::all(['id', 'name']) as $category)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="category-{{ $category->id }}" name="category_id"
                                               class="custom-control-input" value="{{ $category->id }}" required
                                               @if($submission->category_id == $category->id) checked @endif>
                                        <label class="custom-control-label"
                                               for="category-{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group card card-body">
                                <label class="d-block">{{ __('Kategori Peserta') }}</label>

                                @foreach(\App\Participant::all(['id', 'name']) as $participant)
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input type="checkbox" id="participant-{{ $participant->id }}"
                                               name="participant_id[]" class="custom-control-input"
                                               value="{{ $participant->id }}"
                                               @if(in_array($participant->id, $submission->participant_ids)) checked @endif>
                                        <label class="custom-control-label"
                                               for="participant-{{ $participant->id }}">{{ $participant->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group">
                                <label for="title">{{ __('Judul') }}</label>
                                <input type="text" name="title" id="title" required
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ $submission->title }}">
                                @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="writer">{{ __('Penulis') }}</label>
                                <input type="text" name="writer" id="writer" required
                                       class="form-control @error('writer') is-invalid @enderror"
                                       value="{{ $submission->writer }}">
                                @error('writer')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="schema">{{ __('Skema') }}</label>
                                <input type="text" name="schema" id="schema"
                                       class="form-control @error('schema') is-invalid @enderror"
                                       value="{{ $submission->schema }}">
                                @error('schema')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="grant">{{ __('Hibah') }}</label>
                                <input type="text" name="grant" id="grant" required
                                       class="form-control @error('grant') is-invalid @enderror"
                                       value="{{ $submission->grant }}">
                                @error('grant')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group card card-body">
                                <label class="d-block">{{ __('Rincian Biaya') }}</label>

                                <div class="d-md-table w-100" id="financial-area">
                                    @foreach(\App\Financial::all(['id', 'name']) as $financial)
                                        <div class="d-md-table-row financial-item">
                                            <div class="text-left d-md-table-cell p-2">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="financial-{{ $financial->id }}"
                                                           name="financial_id[]" class="custom-control-input"
                                                           value="{{ $financial->id }}"
                                                           @if(in_array($financial->id, $submission->financial_ids)) checked @endif>

                                                    <label class="custom-control-label"
                                                           for="financial-{{ $financial->id }}">{{ $financial->name }}</label>
                                                </div>
                                            </div>

                                            <div class="d-md-table-cell p-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label for="financial-value-{{ $financial->id }}"
                                                               class="d-block m-0 input-group-text">Rp</label>
                                                    </div>

                                                    <input id="financial-value-{{ $financial->id }}" type="number"
                                                           class="form-control"
                                                           name="financial_value[{{ $financial->id }}]"
                                                           value="{{ $submission->financial_values[$financial->id] ?? 0 }}"
                                                           min="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-md-table-row">
                                            <div class="text-left d-md-table-cell py-md-0 pb-md-2 px-2 align-middle">
                                                @error('financial_id.' . $loop->index)
                                                <span class="invalid-feedback d-block"
                                                      role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="d-md-table-cell py-md-0 pb-md-2 px-2 align-middle">
                                                @error('financial_value.' . $financial->id)
                                                <span class="invalid-feedback d-block"
                                                      role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="d-md-table-row">
                                        <div class="text-md-center text-left d-md-table-cell p-2">
                                            <label class="m-0 font-weight-bold text-md-center"
                                                   for="finance-value-total">Total</label>
                                        </div>

                                        <div class="d-md-table-cell p-2">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <label for="financial-value-total"
                                                           class="input-group-text">Rp</label>
                                                </div>

                                                <input id="financial-value-total" type="number" class="form-control"
                                                       value="{{ $submission->financials()->withPivot(['amount'])->newPivot()->where('submission_id', '=', $submission->id)->sum('amount') }}"
                                                       min="0" disabled="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group card card-body">
                                <label class="mb-3 d-block">Lampiran</label>

                                <div class="d-md-table w-100 @error('attachment') is-invalid @enderror">
                                    @foreach(\App\Attachment::all(['id', 'name']) as $attachment)
                                        <div class="d-md-table-row">
                                            <div class="text-left d-md-table-cell p-2 align-middle">
                                                <label class="m-0"
                                                       for="attachment-{{ $attachment->id }}">{{ $attachment->name }}</label>
                                            </div>

                                            <div class="d-md-table-cell p-2 align-middle">
                                                <input type="file" class="form-control-file"
                                                       id="attachment-{{ $attachment->id }}"
                                                       name="attachment[{{ $attachment->id }}]"
                                                       accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.ms-excel,application/pdf,image/png,image/jpeg,image/jpeg,image/jpeg">
                                            </div>
                                        </div>
                                        @if(isset($submission->attachments[$attachment->id]))
                                            <div class="d-md-table-row">
                                                <div
                                                    class="text-left d-md-table-cell py-md-0 pb-md-2 px-2 align-middle">
                                                    <a href="{{ route('submission.attachment', ['submission' => $submission, 'attachmentSubmission' => $submission->attachments[$attachment->id]]) }}"><span>{{ $submission->attachments[$attachment->id]->file_name }}</span></a>
                                                </div>

                                                <div class="d-md-table-cell py-md-0 pb-md-2 px-2 align-middle">
                                                    <span
                                                        class="custom-control custom-checkbox custom-control-inline"><input
                                                            type="checkbox" id="delete-attachment-{{ $attachment->id }}"
                                                            name="delete_attachment[]" class="custom-control-input"
                                                            value="{{ $attachment->id }}"><label
                                                            class="custom-control-label"
                                                            for="delete-attachment-{{ $attachment->id }}">Hapus</label></span>
                                                </div>
                                            </div>
                                        @endif
                                        @error('attachment.' . $attachment->id)
                                        <div class="d-md-table-row">
                                            <div
                                                class="text-left d-md-table-cell py-md-0 pb-md-2 px-2 align-middle"></div>

                                            <div class="d-md-table-cell py-md-0 pb-md-2 px-2 align-middle">
                                                <span class="invalid-feedback d-block"
                                                      role="alert">{{ $message }}</span>
                                            </div>
                                        </div>
                                        @enderror
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('submission.show', compact('submission')) }}"
                                   class="btn btn-success mr-2">{{ __('Lihat') }}</a>
                                <a href="{{ route('submission.pdf', compact('submission')) }}"
                                   class="btn btn-secondary mr-2">{{ __('PDF') }}</a>
                                <a href="#" data-toggle="modal" data-target="#delete"
                                   class="btn btn-danger mr-2">{{ __('Hapus') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Ubah') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('body')
    <script type="text/javascript">
        function data() {
            return {
                status: '{{ $submission->status }}',
                statusOnChange(val) {
                    this.status = val;
                },
            }
        }

        function calculateFinance(e) {
            let total = 0;

            jQuery('.financial-item').each(function (i, element) {
                let el = jQuery(element);
                let checked = el.find('input[type="checkbox"]').is(':checked');

                if (checked) {
                    let val = el.find('input[type="number"]').val();
                    val = val ? val : 0;

                    total += parseInt(val);
                }
            });

            jQuery('#financial-value-total').val(total);
        }

        jQuery('.financial-item input[type="number"], .financial-item input[type="checkbox"]').on('change keyup', calculateFinance);
        jQuery(document).ready(calculateFinance);
    </script>

    <x-modal type="form"
             id="delete"
             method="delete"
             :action="route('submission.destroy', compact('submission'))"
             :title="__('Konfirmasi Penghapusan')"
             classes="modal-dialog-centered modal-dialog-scrollable"
             :message="__('Ingin menghapus pengajuan ini?')">
    </x-modal>
@endpush
