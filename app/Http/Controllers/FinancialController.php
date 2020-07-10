<?php

namespace App\Http\Controllers;

use App\Financial;
use App\Http\Requests\FinancialRequest;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Financial::query();
        $params = [];

        if ($request->has('cari')) {
            $search = $request->get('cari');
            $where = '%' . $search . '%';
            $query->where('name', 'LIKE', $where);
            $params['cari'] = $search;
        }

        $query->orderBy('name');
        $query->select(['id', 'name']);

        $financials = $query->paginate()->appends($params);

        return view('financial.index', compact('financials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('financial.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FinancialRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FinancialRequest $request)
    {
        $data = $request->validated();
        $financial = Financial::create($data);

        if ($financial) {
            return redirect()->route('financial.edit', compact('financial'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menambahkan biaya baru.'),
            ]);
        }

        return redirect()->back()->withInput()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menambahkan biaya baru.'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Financial $financial
     * @return \Illuminate\Http\Response
     */
    public function show(Financial $financial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Financial $financial
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Financial $financial)
    {
        return view('financial.edit', compact('financial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FinancialRequest $request
     * @param Financial $financial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FinancialRequest $request, Financial $financial)
    {
        $data = $request->validated();

        if ($financial->update($data)) {
            return redirect()->route('financial.edit', compact('financial'))->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil mengubah data biaya.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal mengubah data biaya.'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Financial $financial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Financial $financial)
    {
        if ($financial->forceDelete()) {
            return redirect()->route('financial.index')->with('notice', [
                'type' => 'success',
                'dismissible' => true,
                'content' => __('Berhasil menghapus data biaya.'),
            ]);
        }

        return redirect()->back()->with('notice', [
            'type' => 'danger',
            'dismissible' => true,
            'content' => __('Gagal menghapus data biaya.'),
        ]);
    }
}
