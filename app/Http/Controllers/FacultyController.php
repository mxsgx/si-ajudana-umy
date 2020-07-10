<?php

namespace App\Http\Controllers;

use App\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Faculty::query();
        $params = [];

        if ($request->has('cari')) {
            $search = $request->get('cari');
            $where = '%' . $search . '%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $search;
        }

        $query->orderBy('name');
        $query->select(['id', 'name']);

        $faculties = $query->paginate()->appends($params);

        return view('faculty.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('faculty.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FacultyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FacultyRequest $request)
    {
        $data = $request->validated();
        $faculty = Faculty::create($data);

        if ($faculty) {
            return redirect()->route('faculty.edit', compact('faculty'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menambahkan fakultas baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menambahkan fakultas baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Faculty $faculty
     * @return \Illuminate\Http\Response
     */
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Faculty $faculty
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Faculty $faculty)
    {
        return view('faculty.edit', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FacultyRequest $request
     * @param Faculty $faculty
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FacultyRequest $request, Faculty $faculty)
    {
        $data = $request->validated();

        if ($faculty->update($data)) {
            return redirect()->route('faculty.edit', compact('faculty'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data fakultas.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data fakultas.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Faculty $faculty
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Faculty $faculty)
    {
        if ($faculty->forceDelete()) {
            return redirect()->route('faculty.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data fakultas.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data fakultas.'),
        ]);
    }
}
