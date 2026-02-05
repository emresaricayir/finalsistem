<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Due;

class ResetMembershipStartDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:reset-start-date {date : New membership start date, e.g. 2025-01-01} {--only= : Comma-separated member IDs to limit the operation} {--years=10 : How many years of dues to (re)build}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk set members\' membership_date and rebuild dues; reattach payments to oldest open dues.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dateInput = $this->argument('date');
        try {
            $newStart = Carbon::parse($dateInput)->startOfDay();
        } catch (\Throwable $e) {
            $this->error('Invalid date: ' . $dateInput);
            return self::FAILURE;
        }

        $limit = $this->option('only');
        $years = (int) $this->option('years');
        if ($years < 1) { $years = 10; }

        $query = Member::query();
        if ($limit) {
            $ids = collect(explode(',', $limit))->filter()->map(fn($v) => (int) trim($v))->all();
            if (count($ids) === 0) {
                $this->error('No valid IDs in --only option.');
                return self::FAILURE;
            }
            $query->whereIn('id', $ids);
        }

        $this->info('Starting reset for members. Target start date: ' . $newStart->toDateString());

        DB::transaction(function () use ($query, $newStart, $years) {
            $count = 0;
            $query->orderBy('id')->chunkById(100, function ($members) use (&$count, $newStart, $years) {
                foreach ($members as $member) {
                    $count++;
                    // 1) Update membership_date
                    $member->membership_date = $newStart->toDateString();
                    $member->save();

                    // 2) Remove unpaid dues
                    $member->dues()->whereIn('status', ['pending', 'overdue'])->delete();

                    // 3) Rebuild dues from new start date for N years
                    $start = $newStart->copy();
                    $end = $start->copy()->addYears($years)->subMonth();
                    $cursor = $start->copy();
                    while ($cursor->lte($end)) {
                        $year = $cursor->year;
                        $month = $cursor->month;
                        $due = Due::firstOrNew([
                            'member_id' => $member->id,
                            'year' => $year,
                            'month' => $month,
                        ]);
                        $due->amount = $member->monthly_dues;
                        $due->due_date = $cursor->copy()->endOfMonth();
                        if ($due->exists && $due->status === 'paid') {
                            // keep as is
                        } else {
                            $due->status = now()->gt($due->due_date) ? 'overdue' : 'pending';
                            $due->paid_date = null;
                        }
                        $due->save();
                        $cursor->addMonth();
                    }

                    // 4) Re-attach payments to oldest open dues
                    $payments = $member->payments()->with('dues')->orderBy('payment_date')->get();
                    foreach ($payments as $payment) {
                        // Detach previous distribution
                        $payment->dues()->detach();

                        $remaining = (float) $payment->amount;
                        if ($remaining <= 0) { continue; }

                        $openDues = $member->dues()
                            ->whereIn('status', ['pending', 'overdue'])
                            ->orderBy('year')
                            ->orderBy('month')
                            ->get();

                        foreach ($openDues as $due) {
                            if ($remaining <= 0) { break; }
                            $use = min($remaining, (float) $due->amount);
                            $payment->dues()->attach($due->id, ['amount' => $use]);
                            if ($use >= (float) $due->amount) {
                                $due->status = 'paid';
                                $due->paid_date = $payment->payment_date;
                                $due->save();
                            }
                            $remaining -= $use;
                        }
                    }
                }
            });
            $this->info('Processed members successfully.');
        });

        return self::SUCCESS;
    }
}




