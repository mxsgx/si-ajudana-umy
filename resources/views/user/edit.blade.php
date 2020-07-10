@extends('layouts.app')

@section('title', __('Ubah Pengguna'))

@section('content')
    <div class="container" x-data="data()">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="d-flex justify-content-between mb-4">
                    <a class="btn btn-outline-secondary" href="{{ route('user.index') }}">&laquo; {{ __('Kembali') }}</a>
                    <a class="btn btn-outline-primary" href="{{ route('user.create') }}">&plus; {{ __('Tambah Baru') }}</a>
                </div>
                <div class="card">
                    <div class="card-header bg-umy">{{ __('Detail Pengguna') }}</div>
                    <div class="card-body">
                        <form action="{{ route('user.update', compact('user')) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">{{ __('Nama') }}</label>
                                <input type="text" required name="name" id="name" class="form-control @error('name') is-invalid @enderror" autocomplete="off" autofocus value="{{ $user->name }}">
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">{{ __('E-Mail') }}</label>
                                <input type="email" required name="email" id="email" class="form-control @error('email') is-invalid @enderror" autocomplete="off" value="{{ $user->email }}">
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="role">{{ __('Role') }}</label>
                                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required @change="roleOnChange($event.target.value)">
                                    @foreach(\App\User::getModel()->roles as $role => $name)
                                        <option value="{{ $role }}" @if($user->role == $role) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <template x-if="role === 'dean'">
                                <div class="form-group">
                                    <label for="faculty_id">{{ __('Fakultas') }}</label>
                                    <select name="faculty_id" id="faculty_id" class="form-control @error('faculty_id') is-invalid @enderror" required>
                                        @foreach(\App\Faculty::all() as $faculty)
                                            <option value="{{ $faculty->id }}" @if($user->faculty_id == $faculty->id) selected @endif>{{ $faculty->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('faculty_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </template>

                            <template x-if="role === 'head-of-program-study'">
                                <div class="form-group">
                                    <label for="study_id">{{ __('Program Studi') }}</label>
                                    <select name="study_id" id="study_id" class="form-control @error('study_id') is-invalid @enderror" required>
                                        @foreach(\App\Study::all() as $study)
                                            <option value="{{ $study->id }}" @if($user->study_id == $study->id) selected @endif>{{ $study->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('study_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </template>

                            <template x-if="role === 'lecturer'">
                                <div class="form-group">
                                    <label for="lecturer_id">{{ __('Dosen') }}</label>
                                    <select name="lecturer_id" id="lecturer_id" class="form-control @error('lecturer_id') is-invalid @enderror" required>
                                        @foreach(\App\Lecturer::whereNotIn('id', \App\User::where('role', '=', 'lecturer')->whereNotNull('lecturer_id')->get(['lecturer_id'])->filter(function ($arr) use ($user) { return $arr['lecturer_id'] != $user->lecturer_id; })->mapToGroups(function ($arr) { return [$arr['lecturer_id']]; })->toArray()[0])->get() as $lecturer)
                                            <option value="{{ $lecturer->id }}" @if($user->lecturer_id == $lecturer->id) selected @endif>{{ $lecturer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('lecturer_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </template>

                            <hr>

                            <div class="form-group">
                                <label for="password">{{ __('Kata Sandi') }}</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="#" data-toggle="modal" data-target="#delete" class="btn btn-danger mr-2">{{ __('Hapus') }}</a>
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
    <x-modal type="form"
             id="delete"
             method="delete"
             :action="route('user.destroy', compact('user'))"
             :title="__('Konfirmasi Penghapusan')"
             classes="modal-dialog-centered modal-dialog-scrollable"
             :message="__('Ingin menghapus pengguna ini?')">
    </x-modal>

    <script type="text/javascript">
        function data() {
            return {
                role: '{{ $user->role }}',
                roleOnChange(val) {
                    this.role = val;
                },
            }
        }
    </script>
@endpush
