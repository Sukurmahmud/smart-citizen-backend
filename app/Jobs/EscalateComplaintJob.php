<?php

namespace App\Jobs;

use App\Models\Complaint;
use App\Models\ComplaintAuditLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EscalateComplaintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Log::info('Complaint Escalation Engine Started...');

        // ৭ দিন ধরে অলস পড়ে থাকা অভিযোগগুলো খুঁজে বের করা
        $delayedComplaints = Complaint::whereIn('status', ['pending', 'investigating'])
            ->where('updated_at', '<=', now()->subDays(7))
            ->get();

        foreach ($delayedComplaints as $complaint) {
            $oldLevel = $complaint->escalation_level;

            if ($oldLevel < 5) { // সর্বোচ্চ লেভেল ৫ (মন্ত্রণালয়)
                $newLevel = $oldLevel + 1;
                
                // ভৌগোলিক লোকেশন অনুযায়ী উপরের কর্মকর্তা খোঁজা
                $nextRepresentativeId = $this->findUpperAuthority($complaint, $newLevel);
                $oldStatus = $complaint->status;

                $complaint->update([
                    'escalation_level' => $newLevel,
                    'current_representative_id' => $nextRepresentativeId,
                    'status' => 'pending'
                ]);

                // অডিট লগে অটোমেটিক এন্ট্রি জেনারেট করা
                ComplaintAuditLog::create([
                    'complaint_id' => $complaint->id,
                    'action_by' => 1, // সিস্টেম সুপার অ্যাডমিন
                    'old_status' => $oldStatus,
                    'new_status' => 'pending',
                    'remarks' => "নির্ধারিত ৭ দিনে সমাধান না হওয়ায় অভিযোগটি স্বয়ংক্রিয়ভাবে লেভেল {$oldLevel} থেকে লেভেল {$newLevel}-এ পাঠানো হয়েছে।",
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'System Escalation Engine'
                ]);

                Log::info("Complaint ID: {$complaint->tracking_number} escalated to Level {$newLevel}");
            }
        }
    }

    private function findUpperAuthority(Complaint $complaint, int $nextLevel): ?int
    {
        $query = User::where('role', 'representative');

        if ($nextLevel == 2) {
            $query->where('union_id', $complaint->union_id);
        } elseif ($nextLevel == 3) {
            $query->where('upazila_id', $complaint->upazila_id);
        } elseif ($nextLevel == 4) {
            $query->where('district_id', $complaint->district_id);
        } elseif ($nextLevel == 5) {
            return User::where('role', 'super_admin')->first()?->id;
        }

        return $query->first()?->id ?? $complaint->current_representative_id;
    }
}