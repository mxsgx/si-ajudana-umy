<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Exports\ReportActivityExport;
use App\Exports\ReportPersonalExport;
use App\Exports\ReportRecapExport;
use App\Exports\ReportUnitExport;
use App\Faculty;
use App\Submission;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function unit(Request $request)
    {
        if (auth()->user()->role != 'admin') {
            abort(401);
        }

        $faculties = Faculty::with('studies')->get();
        $yearsQuery = Submission::query();
        $submissionQuery = Submission::query();

        if (request()->has('study_id') && request()->get('study_id')) {
            $yearsQuery->whereHas('lecturer', function ($query) {
                $query->where('study_id', '=', request()->get('study_id'));
            });

            $submissionQuery->whereHas('lecturer', function ($query) {
                $query->where('study_id', '=', request()->get('study_id'));
            });
        }

        if (request()->has('year') && request()->get('year')) {
            $submissionQuery->whereYear('date_start', '=', request()->get('year'));
        }

        if (isset(Submission::getModel()->statuses[request()->get('status')])) {
            $submissionQuery->where('status', request()->get('status'));
        }

        $years = $yearsQuery->selectRaw('date_format(date_start, "%Y") as date_year')->distinct()->get()->map(function ($submission) {
            return $submission->date_year;
        });

        $submissions = $submissionQuery->with('financials')->cursor()->collect()->map(function ($submission) {
            return [
                'lecturer' => $submission->lecturer->name,
                'name' => $submission->name,
                'title' => $submission->title,
                'date' => $submission->date_start->format('d-m-Y'),
                'cost' => $submission->financials()->sum('amount'),
            ];
        });

        if ($request->has('action') && $request->get('action') === 'export') {
            return Excel::download(new ReportUnitExport, 'LAPORAN-UNIT.xlsx');
        }

        return view('report.unit', compact('faculties', 'years', 'submissions'));
    }

    public function personal(Request $request)
    {
        if (auth()->user()->role != 'admin') {
            abort(401);
        }

        $faculties = Faculty::with('studies')->get();
        $yearsQuery = Submission::query();
        $submissionQuery = Submission::query();

        if (request()->has('lecturer_id') && request()->get('lecturer_id')) {
            $yearsQuery->where('lecturer_id', request()->get('lecturer_id'));
            $submissionQuery->where('lecturer_id', request()->get('lecturer_id'));
        }

        if (request()->has('year') && request()->get('year')) {
            $submissionQuery->whereYear('date_start', '=', request()->get('year'));
        }

        if (isset(Submission::getModel()->statuses[request()->get('status')])) {
            $submissionQuery->where('status', request()->get('status'));
        }

        $years = $yearsQuery->selectRaw('date_format(date_start, "%Y") as date_year')->distinct()->get()->map(function ($submission) {
            return $submission->date_year;
        });

        $submissions = $submissionQuery->with('financials')->cursor()->collect()->map(function ($submission) {
            return [
                'lecturer' => $submission->lecturer->name,
                'name' => $submission->name,
                'title' => $submission->title,
                'date' => $submission->date_start->format('d-m-Y'),
                'cost' => $submission->financials()->sum('amount'),
            ];
        });

        if ($request->has('action') && $request->get('action') === 'export') {
            return Excel::download(new ReportPersonalExport, 'LAPORAN-PERSONAL.xlsx');
        }

        return view('report.personal', compact('years', 'submissions', 'faculties'));
    }

    public function activity()
    {
        if (auth()->user()->role != 'admin') {
            abort(401);
        }

        $activities = Activity::all();
        $yearsQuery = Submission::query();
        $submissionQuery = Submission::query();

        if (request()->has('activity_id') && request()->get('activity_id')) {
            $yearsQuery->where('activity_id', request()->get('activity_id'));
            $submissionQuery->where('activity_id', request()->get('activity_id'));
        }

        if (request()->has('year') && request()->get('year')) {
            $submissionQuery->whereYear('date_start', '=', request()->get('year'));
        }

        if (isset(Submission::getModel()->statuses[request()->get('status')])) {
            $submissionQuery->where('status', request()->get('status'));
        }

        $years = $yearsQuery->selectRaw('date_format(date_start, "%Y") as date_year')->distinct()->get()->map(function ($submission) {
            return $submission->date_year;
        });

        $submissions = $submissionQuery->with('financials')->cursor()->collect()->map(function ($submission) {
            return [
                'lecturer' => $submission->lecturer->name,
                'name' => $submission->name,
                'title' => $submission->title,
                'study' => $submission->lecturer->study->name,
                'cost' => $submission->financials()->sum('amount'),
            ];
        });

        if (request()->has('action') && request()->get('action') === 'export') {
            return Excel::download(new ReportActivityExport, 'LAPORAN-KEGIATAN.xlsx');
        }

        return view('report.activity', compact('activities', 'years', 'submissions'));
    }

    public function recap()
    {
        if (auth()->user()->role != 'admin') {
            abort(401);
        }

        $activities = Activity::query();
        $years = Submission::query()->selectRaw('date_format(date_start, "%Y") as date_year')->distinct()->get()->map(function ($submission) {
            return $submission->date_year;
        });

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

        if (request()->has('action') && request()->get('action') === 'export') {
            return Excel::download(new ReportRecapExport, 'LAPORAN-REKAP.xlsx');
        }

        return view('report.recap', [
            'years' => $years,
            'activities' => $activities,
            'calc' => $calc,
        ]);
    }
}
