<?php

namespace App\Http\Controllers;

use App\AttachmentSubmission;
use App\FinancialSubmission;
use App\Http\Requests\SubmissionRequest;
use App\ParticipantSubmission;
use App\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $query = Submission::query();
        $params = [];

        if ($request->has('cari')) {
            $keyword = $request->get('cari');
            $where = '%'.$keyword.'%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $keyword;
        }

        if ($role === 'lecturer') {
            $query->where('lecturer_id', '=', auth()->user()->lecturer_id);
        } elseif ($role === 'dean') {
            $faculty_id = auth()->user()->faculty_id;
            $query->whereHas('lecturer', function ($q) use ($faculty_id) {
                $q->whereHas('study', function ($q) use ($faculty_id) {
                    $q->where('faculty_id', '=', $faculty_id);
                });
            })->whereIn('status', ['approved-co-dean', 'approved', 'rejected']);
        } elseif ($role === 'co-dean-1') {
            $faculty_id = auth()->user()->faculty_id;
            $query->whereHas('lecturer', function ($q) use ($faculty_id) {
                $q->whereHas('study', function ($q) use ($faculty_id) {
                    $q->where('faculty_id', '=', $faculty_id);
                });
            })->whereNotIn('status', ['unauthorized', 'authorized']);
        } elseif ($role === 'co-dean-2') {
            $faculty_id = auth()->user()->faculty_id;
            $query->whereHas('lecturer', function ($q) use ($faculty_id) {
                $q->whereHas('study', function ($q) use ($faculty_id) {
                    $q->where('faculty_id', '=', $faculty_id);
                });
            })->whereNotIn('status', ['unauthorized']);
        } elseif ($role === 'head-of-program-study') {
            $study_id = auth()->user()->study_id;
            $query->whereHas('lecturer', function ($q) use ($study_id) {
                $q->where('study_id', '=', $study_id);
            });
        }

        $query->orderByDesc('date_start');

        $submissions = $query->paginate()->appends($params);

        return view('submission.index', compact('submissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('submission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubmissionRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SubmissionRequest $request)
    {
        $role = auth()->user()->role;
        $data = $request->validated();

        if ($role === 'lecturer') {
            $data['lecturer_id'] = auth()->user()->lecturer_id;
            $data['status'] = 'unauthorized';
        }

        $submission = Submission::create(collect($data)->except([
            'participant_value',
            'attachment',
            'participant_id',
            'financial_id',
        ])->toArray());

        if ($submission) {
            if (isset($data['attachment'])) {
                foreach ($data['attachment'] as $attachment_id => $file) {
                    $fileName = hash('sha256', Str::uuid().Str::random()).'.'.$file->getClientOriginalExtension();
                    $fileDir = storage_path('attachments');

                    if ($file->move($fileDir, $fileName)) {
                        AttachmentSubmission::create([
                            'attachment_id' => $attachment_id,
                            'submission_id' => $submission->id,
                            'name' => $fileName,
                            'file_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }

            if (isset($data['financial_id'])) {
                foreach ($data['financial_id'] as $financial_id) {
                    $amount = $data['financial_value'][$financial_id];

                    FinancialSubmission::create([
                        'financial_id' => $financial_id,
                        'amount' => $amount,
                        'submission_id' => $submission->id,
                    ]);
                }
            }

            if (isset($data['participant_id'])) {
                foreach ($data['participant_id'] as $participant_id) {
                    ParticipantSubmission::create([
                        'submission_id' => $submission->id,
                        'participant_id' => $participant_id,
                    ]);
                }
            }

            return redirect()->route('submission.edit', compact('submission'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil membuat pengajuan baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal membuat pengajuan baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Submission  $submission
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Submission $submission)
    {
        return view('submission.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Submission  $submission
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Submission $submission)
    {
        return view('submission.edit', compact('submission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SubmissionRequest  $request
     * @param  Submission  $submission
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(SubmissionRequest $request, Submission $submission)
    {
        $role = auth()->user()->role;
        $data = $request->validated();

        if ($role === 'lecturer') {
            $data['lecturer_id'] = auth()->user()->lecturer_id;
        }

        if (!isset($data['financial_id'])) {
            $data['financial_id'] = [];
        }

        if (!isset($data['participant_id'])) {
            $data['participant_id'] = [];
        }

        $success = $submission->update(collect($data)->except([
            'financial_value',
            'financial_id',
            'attachment',
            'participant_id'
        ])->toArray());

        $oldFinancial = $submission->financials()->get()->map(function ($financial) {
            return (string) $financial->id;
        });
        $removeFinancial = $oldFinancial->count() > 0 ? $oldFinancial->diff($data['financial_id']) : collect();
        $newFinancial = collect($data['financial_id'])->diff($oldFinancial);

        foreach ($submission->financials as $financial) {
            if (in_array($financial->id, $removeFinancial->toArray())) {
                $financial->pivot->delete();
            } else {
                if (isset($data['financial_value'][$financial->id])) {
                    $financial->pivot->update([
                        'amount' => $data['financial_value'][$financial->id]
                    ]);
                }
            }
        }

        $newFinancial->each(function ($financialId) use ($submission, $data) {
            FinancialSubmission::create([
                'financial_id' => $financialId,
                'submission_id' => $submission->id,
                'amount' => $data['financial_value'][$financialId],
            ]);
        });

        $oldParticipant = $submission->participants()->get()->map(function ($participant) {
            return (string) $participant->id;
        });
        $removeParticipant = $oldParticipant->count() > 0 ? $oldParticipant->diff($data['participant_id']) : collect();
        $newParticipant = collect($data['participant_id'])->diff($oldParticipant);

        foreach ($submission->participants as $participant) {
            if (in_array($participant->id, $removeParticipant->toArray())) {
                $participant->pivot->delete();
            }
        }

        $newParticipant->each(function ($participantId) use ($submission) {
            ParticipantSubmission::create([
                'participant_id' => $participantId,
                'submission_id' => $submission->id,
            ]);
        });

        if (isset($data['delete_attachment'])) {
            $submission->attachments()
                ->withPivot(['file_name', 'name'])->get()->each(function ($attachment) use ($data) {
                    if (in_array($attachment->id, $data['delete_attachment'])) {
                        $fileName = $attachment->pivot->file_name;
                        $name = $attachment->pivot->name;
                        $filePath = storage_path('attachments/'.$name);

                        if (\File::exists($filePath)) {
                            \File::delete($filePath);
                        }

                        $attachment->pivot->delete();
                    }
                });
        }

        if (isset($data['attachment'])) {
            foreach ($data['attachment'] as $attachment_id => $file) {
                $query = $submission->attachments()->newPivot()
                    ->where('attachment_id', '=', $attachment_id)
                    ->where('submission_id', '=', $submission->id);
                if ($query->exists()) {
                    $existedAttachment = $query->first();
                    $existedName = $existedAttachment->name;
                    $existedFilePath = storage_path('attachments/'.$existedName);

                    if (\File::exists($existedFilePath)) {
                        \File::delete($existedFilePath);
                    }

                    $existedAttachment->delete();
                }

                $fileName = hash('sha256', Str::uuid().Str::random()).'.'.$file->getClientOriginalExtension();
                $fileDir = storage_path('attachments');

                if ($file->move($fileDir, $fileName)) {
                    AttachmentSubmission::create([
                        'attachment_id' => $attachment_id,
                        'submission_id' => $submission->id,
                        'name' => $fileName,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        if ($success) {
            return redirect()->route('submission.edit', compact('submission'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data pengajuan.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Submission  $submission
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Submission $submission)
    {
        $submission->attachments()->withPivot(['name'])->get()->each(function ($attachment) {
            $filePath = storage_path('attachments/'.$attachment->pivot->name);

            if (\File::exists($filePath)) {
                \File::delete($filePath);
            }

            $attachment->pivot->delete();
        });
        $submission->financials()->get()->each(function ($financial) {
            $financial->pivot->delete();
        });
        $submission->participants()->get()->each(function ($participant) {
            $participant->pivot->delete();
        });

        if ($submission->delete()) {
            return redirect()->route('submission.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data pengajuan.'),
        ]);
    }

    /**
     * Show the specified attachment resource from storage.
     *
     * @param  Submission  $submission
     * @param  AttachmentSubmission  $attachmentSubmission
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function attachment(Submission $submission, AttachmentSubmission $attachmentSubmission)
    {
        return response()->file(storage_path('attachments/'.$attachmentSubmission->name));
    }

    public function authorizeSubmission(Request $request, Submission $submission)
    {
        if ($submission->status === 'unauthorized') {
            $data = [
                'status' => 'authorized',
            ];

            if (auth()->user()->role === 'admin') {
                $this->validate($request, [
                    'authorized_by' => ['required', 'exists:users,id'],
                ]);

                $data['authorized_by'] = $request->get('authorized_by');
            } elseif (auth()->user()->role === 'head-of-program-study') {
                $data['authorized_by'] = auth()->user()->id;
            }

            if ($submission->update($data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Berhasil mengauthorisasi pengajuan.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal mengauthorisasi pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah diauthorisasi.'),
        ]);
    }

    public function authorizeCoDean(Request $request, Submission $submission)
    {
        if ($submission->status === 'authorized') {
            $data = [
                'status' => 'authorized-co-dean',
            ];

            if (auth()->user()->role === 'admin') {
                $this->validate($request, [
                    'authorized_by_co_dean' => ['required', 'exists:users,id'],
                ]);

                $data['authorized_by_co_dean'] = $request->get('authorized_by_co_dean');
            } elseif (auth()->user()->role === 'co-dean-2') {
                $data['authorized_by_co_dean'] = auth()->user()->id;
            }

            if ($submission->update($data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Berhasil mengetahui pengajuan.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal mengetahui pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah diketahui.'),
        ]);
    }

    public function approveCoDean(Request $request, Submission $submission)
    {
        if ($submission->status === 'authorized-co-dean' || $submission->status === 'revision-co-dean') {
            $data = [
                'status' => 'approved-co-dean',
            ];

            if (auth()->user()->role === 'admin') {
                $this->validate($request, [
                    'approved_by_co_dean' => ['required', 'exists:users,id'],
                ]);

                $data['approved_by_co_dean'] = $request->get('approved_by_co_dean');
            } elseif (auth()->user()->role === 'co-dean-1') {
                $data['approved_by_co_dean'] = auth()->user()->id;
            }

            if ($submission->update($data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Berhasil menyetujui pengajuan.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal menyetujui pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah disetujui.'),
        ]);
    }

    public function approve(Request $request, Submission $submission)
    {
        if ($submission->status === 'approved-co-dean') {
            $data = [
                'status' => 'approved',
            ];

            if (auth()->user()->role === 'admin') {
                $this->validate($request, [
                    'approved_by' => ['required', 'exists:users,id'],
                ]);

                $data['approved_by'] = $request->get('approved_by');
            } elseif (auth()->user()->role === 'co-dean-2') {
                $data['approved_by'] = auth()->user()->id;
            }

            if ($submission->update($data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Berhasil menyetujui pengajuan.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal menyetujui pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah disetujui.'),
        ]);
    }

    public function revisionCoDean(Submission $submission)
    {
        $data = request()->validate(['note' => 'nullable|string']);
        if ($submission->status === 'authorized-co-dean') {
            if ($submission->update([
                    'status' => 'revision-co-dean',
                ] + $data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Pengajuan telah direvisi.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal merevisi pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah direvisi.'),
        ]);
    }

    public function rejectCoDean(Submission $submission)
    {
        $data = request()->validate(['note' => 'nullable|string']);
        if ($submission->status === 'authorized-co-dean' || $submission->status === 'revision-co-dean') {
            if ($submission->update([
                    'status' => 'rejected-co-dean',
                ] + $data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Pengajuan telah ditolak.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal menolak pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah ditolak.'),
        ]);
    }

    public function reject(Submission $submission)
    {
        $data = request()->validate(['note' => 'nullable|string']);
        if ($submission->status === 'authorized') {
            if ($submission->update([
                    'status' => 'rejected',
                ] + $data)) {
                return redirect()->back()->with('notice', [
                    'type' => 'success',
                    'dismissible' => true,
                    'content' => __('Pengajuan telah ditolak.'),
                ]);
            }

            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal menolak pengajuan.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'info',
            'dismissible' => true,
            'content' => __('Pengajuan sudah ditolak.'),
        ]);
    }

    public function pdf(Submission $submission)
    {
        return \PDF::loadView('submission.pdf', compact('submission'))->stream();
    }
}
