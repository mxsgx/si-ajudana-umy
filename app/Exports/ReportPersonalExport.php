<?php

namespace App\Exports;

use App\Submission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportPersonalExport implements FromView
{
    public function view(): View
    {
        $submissionQuery = Submission::query();

        if (request()->has('lecturer_id') && request()->get('lecturer_id')) {
            $submissionQuery->where('lecturer_id', request()->get('lecturer_id'));
        }

        if (request()->has('year') && request()->get('year')) {
            $submissionQuery->whereYear('date_start', '=', request()->get('year'));
        }

        if (isset(Submission::getModel()->statuses[request()->get('status')])) {
            $submissionQuery->where('status', request()->get('status'));
        }

        $submissions = $submissionQuery->with('financials')->cursor()->collect()->map(function ($submission) {
            return [
                'name' => $submission->name,
                'date' => $submission->date_start->format('d-m-Y'),
                'cost' => $submission->financials()->sum('amount'),
            ];
        });

        return view('export.report-unit', compact('submissions'));
    }
}
