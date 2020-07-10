<?php

namespace App\Http\Controllers;

use App\Attachment;
use App\Http\Requests\AttachmentRequest;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Attachment::query();
        $params = [];

        if ($request->has('cari')) {
            $search = $request->get('cari');
            $where = '%' . $search . '%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $search;
        }

        $query->orderBy('name');
        $query->select(['id', 'name']);

        $attachments = $query->paginate()->appends($params);

        return view('attachment.index', compact('attachments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('attachment.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AttachmentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AttachmentRequest $request)
    {
        $data = $request->validated();
        $attachment = Attachment::create($data);

        if ($attachment) {
            return redirect()->route('attachment.edit', compact('attachment'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menambahkan lampiran baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menambahkan lampiran baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Attachment $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Attachment $attachment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Attachment $attachment)
    {
        return view('attachment.edit', compact('attachment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AttachmentRequest $request
     * @param Attachment $attachment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AttachmentRequest $request, Attachment $attachment)
    {
        $data = $request->validated();

        if ($attachment->update($data)) {
            return redirect()->route('attachment.edit', compact('attachment'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data lampiran.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data lampiran.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Attachment $attachment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Attachment $attachment)
    {
        if ($attachment->forceDelete()) {
            return redirect()->route('attachment.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data lampiran.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data lampiran.'),
        ]);
    }
}
