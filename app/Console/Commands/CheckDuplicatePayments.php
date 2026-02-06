<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDuplicatePayments extends Command
{
    protected $signature = 'payments:check-duplicates {member_id?}';
    protected $description = 'Check for duplicate payments for a member or all members';

    public function handle()
    {
        $memberId = $this->argument('member_id');
        
        if ($memberId) {
            $member = Member::find($memberId);
            if (!$member) {
                $this->error("Member with ID {$memberId} not found.");
                return Command::FAILURE;
            }
            $this->checkMemberPayments($member);
        } else {
            $this->info('Checking all members for duplicate payments...');
            $members = Member::all();
            $totalDuplicates = 0;
            
            foreach ($members as $member) {
                $duplicates = $this->checkMemberPayments($member, false);
                $totalDuplicates += $duplicates;
            }
            
            $this->info("\nTotal duplicate payments found: {$totalDuplicates}");
        }
        
        return Command::SUCCESS;
    }
    
    private function checkMemberPayments(Member $member, $verbose = true)
    {
        if ($verbose) {
            $this->info("\nChecking member: {$member->full_name} (ID: {$member->id})");
        }
        
        // Get all payments for this member
        $payments = Payment::where('member_id', $member->id)
            ->orderBy('payment_date')
            ->orderBy('created_at')
            ->get();
        
        $duplicates = [];
        $checked = [];
        
        foreach ($payments as $payment) {
            $key = $payment->payment_date . '|' . $payment->amount . '|' . $payment->payment_method;
            
            if (isset($checked[$key])) {
                // This is a duplicate
                $duplicates[] = [
                    'original' => $checked[$key],
                    'duplicate' => $payment
                ];
            } else {
                $checked[$key] = $payment;
            }
        }
        
        if (count($duplicates) > 0) {
            if ($verbose) {
                $this->warn("Found " . count($duplicates) . " duplicate payment(s):");
            }
            
            foreach ($duplicates as $dup) {
                $original = $dup['original'];
                $duplicate = $dup['duplicate'];
                
                if ($verbose) {
                    $this->line("  - Original: ID {$original->id}, Date: {$original->payment_date}, Amount: {$original->amount}€, Created: {$original->created_at}");
                    $this->line("    Duplicate: ID {$duplicate->id}, Date: {$duplicate->payment_date}, Amount: {$duplicate->amount}€, Created: {$duplicate->created_at}");
                }
            }
            
            return count($duplicates);
        } else {
            if ($verbose) {
                $this->info("No duplicates found.");
            }
            return 0;
        }
    }
}
