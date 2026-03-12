<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedFromCsv('filieres', database_path('data/filieres.csv'));
        $this->seedFromCsv('groups', database_path('data/groups.csv'));
        $this->seedFromCsv('modules', database_path('data/modules.csv'));
        
        $this->seedUsers(database_path('data/users.csv'));
        $this->seedStudentProfiles(database_path('data/student_profiles.csv'));
        $this->seedTeacherProfiles(database_path('data/teacher_profiles.csv'));

        $this->seedFromCsv('academic_sessions', database_path('data/sessions.csv'));
        // $this->seedFromCsv('attendance_records', database_path('data/attendance_records.csv'));
        // $this->seedFromCsv('justifications', database_path('data/justifications.csv'));
    }

    protected function seedUsers(string $path)
    {
        if (!File::exists($path)) return;
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
        while (($row = fgetcsv($file)) !== false) {
            $userData = array_combine($header, $row);
            $role = $userData['role'];
            unset($userData['role']);
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($userData['password']);
            $user = \App\Models\User::create($userData);
            // $user->assignRole($role); // Roles will be set after Spatie setup
        }
        fclose($file);
    }

    protected function seedStudentProfiles(string $path)
    {
        if (!File::exists($path)) return;
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            $user = \App\Models\User::where('email', $data['email'])->first();
            if ($user) {
                DB::table('student_profiles')->insert([
                    'user_id' => $user->id,
                    'matricule' => $data['matricule'],
                    'group_id' => $data['group_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        fclose($file);
    }

    protected function seedTeacherProfiles(string $path)
    {
        if (!File::exists($path)) return;
        $file = fopen($path, 'r');
        $header = fgetcsv($file);
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            $user = \App\Models\User::where('email', $data['email'])->first();
            if ($user) {
                DB::table('teacher_profiles')->insert([
                    'user_id' => $user->id,
                    'specialty' => $data['specialty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        fclose($file);
    }

    protected function seedFromCsv(string $table, string $path)
    {
        if (!File::exists($path)) {
            $this->command->warn("CSV file not found: {$path}");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        $data = [];
        while (($row = fgetcsv($file)) !== false) {
            $item = array_combine($header, $row);
            $item['created_at'] = now();
            $item['updated_at'] = now();
            $data[] = $item;
            
            if (count($data) >= 100) {
                DB::table($table)->insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table($table)->insert($data);
        }

        fclose($file);
        $this->command->info("Seeded {$table} from CSV.");
    }
}
