<?php

namespace App\Exports;

use App\Submission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportActivityExport implements FromView
{
    public function view(): View
    {
        $submissionQuery = Submission::query();

        if (request()->has('activity_id') && request()->get('activity_id')) {
            $submissionQuery->where('activity_id', request()->get('activity_id'));
        }

        if (request()->has('year') && request()->get('year')) {
            $submissionQuery->whereYear('date_start', '=', request()->get('year'));
        }

        if (isset(Submission::getModel()->statuses[request()->get('status')])) {
            $submissionQuery->where('status', request()->get('status'));
        }

        $submissions = $submissionQuery->with('financials')->cursor()->collect()->map(function ($submission) {
            return [
                'lecturer' => $submission->lecturer->name,
                'name' => $submission->name,
                'title' => $submission->title,
                'study' => $submission->lecturer->study->name,
                'cost' => $submission->financials()->sum('amount'),
            ];
        });

        return view('export.report-activity', compact('submissions'));
    }
}
