<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Session;
use App\Models\Module;
use App\Models\Group;
use Carbon\Carbon;

class CreateTestSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an active session for the demo teacher to test attendance entry.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teacher = User::where('email', 'imane@ams.com')->first();
        if (!$teacher || !$teacher->teacherProfile) {
            $this->error('Demo teacher (imane@ams.com) not found.');
            return;
        }

        $module = Module::first();
        $group = Group::first();

        if (!$module || !$group) {
            $this->error('No modules or groups found. Run seeders first.');
            return;
        }

        // Create a session that is active NOW
        $session = Session::create([
            'module_id' => $module->id,
            'teacher_profile_id' => $teacher->teacherProfile->id,
            'group_id' => $group->id,
            'start_time' => Carbon::now()->subHour(),
            'end_time' => Carbon::now()->addHours(2),
            'duration_hours' => 3.0,
            'type' => 'TP',
        ]);

        $this->info("Test session created successfully!");
        $this->info("Module: {$module->name}");
        $this->info("Group: {$group->name}");
        $this->info("Time: " . $session->start_time->format('H:i') . " to " . $session->end_time->format('H:i'));
    }
}
