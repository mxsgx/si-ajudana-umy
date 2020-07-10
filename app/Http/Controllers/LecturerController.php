<?php

namespace App\Http\Controllers;

use App\Http\Requests\LecturerRequest;
use App\Lecturer;
use App\User;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Lecturer::query();
        $params = [];

        if ($request->has('cari')) {
            $search = $request->get('cari');
            $where = '%' . $search . '%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $search;
        }

        $query->orderBy('name');
        $query->select(['id', 'name']);

        $lecturers = $query->paginate()->appends($params);

        return view('lecturer.index', compact('lecturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('lecturer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LecturerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LecturerRequest $request)
    {
        $data = $request->validated();
        $lecturer = Lecturer::create($data);

        if ($lecturer) {
            return redirect()->route('lecturer.edit', compact('lecturer'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menambahkan dosen baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menambahkan dosen baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Lecturer $lecturer
     * @return \Illuminate\Http\Response
     */
    public function show(Lecturer $lecturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lecturer $lecturer
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Lecturer $lecturer)
    {
        return view('lecturer.edit', compact('lecturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LecturerRequest $request
     * @param Lecturer $lecturer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LecturerRequest $request, Lecturer $lecturer)
    {
        $data = $request->validated();

        if ($lecturer->update($data)) {
            return redirect()->route('lecturer.edit', compact('lecturer'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data dosen.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data dosen.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lecturer $lecturer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Lecturer $lecturer)
    {
        $user = User::where('lecturer_id', '=', $lecturer->id)->first();

        if ($user && !$user->forceDelete()) {
            return redirect()->back()->with('notice', [
                'type' => 'danger',
                'dismissible' => true,
                'content' => __('Gagal menghapus data pengguna.'),
            ]);
        }

        if ($lecturer->forceDelete()) {
            return redirect()->route('lecturer.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data dosen.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data dosen.'),
        ]);
    }
}
