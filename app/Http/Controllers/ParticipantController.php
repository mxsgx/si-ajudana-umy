<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Participant::query();
        $params = [];

        if ($request->has('cari')) {
            $search = $request->get('cari');
            $where = '%' . $search . '%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $search;
        }

        $query->orderBy('name');
        $query->select(['id', 'name']);

        $participants = $query->paginate()->appends($params);

        return view('participant.index', compact('participants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('participant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ParticipantRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ParticipantRequest $request)
    {
        $data = $request->validated();
        $participant = Participant::create($data);

        if ($participant) {
            return redirect()->route('participant.edit', compact('participant'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menambahkan peserta baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menambahkan peserta baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Participant $participant
     * @return \Illuminate\Http\Response
     */
    public function show(Participant $participant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Participant $participant
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Participant $participant)
    {
        return view('participant.edit', compact('participant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ParticipantRequest $request
     * @param Participant $participant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ParticipantRequest $request, Participant $participant)
    {
        $data = $request->validated();

        if ($participant->update($data)) {
            return redirect()->route('participant.edit', compact('participant'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data peserta.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data peserta.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Participant $participant
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Participant $participant)
    {
        if ($participant->forceDelete()) {
            return redirect()->route('participant.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data peserta.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data peserta.'),
        ]);
    }
}
