<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = storage_path('backup/db.json');

        if (File::exists($filePath)) {
            $file = File::get($filePath);
            $json = json_decode($file, true);
            $collection = collect($json);
            $collection->where('name', 'submissions')->first(function ($result) {
                foreach ($result['data'] as $data) {
                    $start_at = Carbon::parse($data['start_at']);
                    $end_at = Carbon::parse($data['end_at']);
                    $data['date_start'] = $start_at->format('Y-m-d');
                    $data['time_start'] = $start_at->format('H:i:s');
                    $data['date_end'] = $end_at->format('Y-m-d');
                    $data['time_end'] = $end_at->format('H:i:s');

                    unset($data['start_at'], $data['end_at']);

                    \App\Submission::create($data);
                }
            });
            $collection->where('name', 'submission_finances')->first(function ($result) {
                foreach ($result['data'] as $data) {
                    \App\FinancialSubmission::create([
                        'submission_id' => $data['submission_id'],
                        'financial_id' => $data['finance_id'],
                        'amount' => $data['value'],
                    ]);
                }
            });
            $collection->where('name', 'submission_participants')->first(function($result) {
                foreach ($result['data'] as $data) {
                    \App\ParticipantSubmission::create([
                        'submission_id' => $data['submission_id'],
                        'participant_id' => $data['participant_id'],
                    ]);
                }
            });
        }
    }
}
