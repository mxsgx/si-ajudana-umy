<?php

namespace App\Exports;

use App\Activity;
use App\Submission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportRecapFundExport implements FromView
{
    public function view(): View
    {
        $activities = Activity::query();

        $activities = $activities->get()->map(function ($activity) {
            return [
                'name' => $activity->name,
                'submissions' => $activity->submissions->filter(function ($submission) {
                    $flag1 = true;
                    $flag2 = true;

                    if (request()->has('year') && request()->get('year')) {
                        $flag1 = $submission->date_start->format('Y') == request()->get('year');
                    }

                    if (isset(Submission::getModel()->statuses[request('status')])) {
                        $flag2 = $submission->status == request('status');
                    }

                    return $flag1 && $flag2;
                })->map(function ($submission) {
                    return [
                        'study' => $submission->lecturer->study->name,
                        'amount' => $submission->financials()->sum('amount'),
                    ];
                })->mapToGroups(function ($data) {
                    return [
                        $data['study'] => [
                            'amount' => $data['amount']
                        ],
                    ];
                }),
            ];
        });

        $calc = $activities->map(function ($data) {
            return collect($data['submissions'])->map(function ($data) {
                return collect($data)->sum('amount');
            });
        })->filter(function ($data) {
            return $data->isNotEmpty();
        });

        return view('export.report-recap-fund', [
            'activities' => $activities,
            'calc' => $calc,
        ]);
    }
}
