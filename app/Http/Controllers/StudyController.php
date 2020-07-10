<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudyRequest;
use App\Study;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Study::query();
        $params = [];

        if ($request->has('cari')) {
            $search = $request->get('cari');
            $where = '%' . $search . '%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $search;
        }

        $query->orderBy('name');
        $query->select(['id', 'name']);

        $studies = $query->paginate()->appends($params);

        return view('study.index', compact('studies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('study.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StudyRequest $request)
    {
        $data = $request->validated();
        $study = Study::create($data);

        if ($study) {
            return redirect()->route('study.edit', compact('study'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menambahkan program studi baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menambahkan program studi baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Study $study
     * @return \Illuminate\Http\Response
     */
    public function show(Study $study)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Study $study
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Study $study)
    {
        return view('study.edit', compact('study'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StudyRequest $request
     * @param Study $study
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudyRequest $request, Study $study)
    {
        $data = $request->validated();

        if ($study->update($data)) {
            return redirect()->route('study.edit', compact('study'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data program studi.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data program studi.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Study $study
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Study $study)
    {
        if ($study->forceDelete()) {
            return redirect()->route('study.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data program studi.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data program studi.'),
        ]);
    }
}
