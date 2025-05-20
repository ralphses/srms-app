<?php

namespace Database\Seeders;

use App\Http\Controllers\StudentController;
use App\Models\AdminUser;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\SchoolSession;
use App\Models\Student;
use App\Models\User;
use App\Utils\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 random users
        User::factory(5)->create();

        $cscDept = Department::query()->create(['name' => 'Computer Science', 'code' => 'CSC']);
        $phyDept = Department::query()->create(['name' => 'Physics', 'code' => 'PHY']);

        // Admin user
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@srms-app.com',
            'password' => Hash::make('password'),
            'role' => Utils::ROLE_ADMIN,
        ]);

        // Lecturer user
        $lecturerUser = User::create([
            'name' => 'Lecturer',
            'email' => 'lecturer@srms-app.com',
            'password' => Hash::make('password'),
            'role' => Utils::ROLE_LECTURER,
        ]);

        // Student user
        $studentUser = User::create([
            'name' => 'Student',
            'email' => 'student@srms-app.com',
            'password' => Hash::make('password'),
            'role' => Utils::ROLE_STUDENT,
        ]);

        // Create a school session
        $session = SchoolSession::create([
            'name' => '2020/2021',
            'current_semester' => Utils::SEMESTER_FIRST,
            'first_semester_start_date' => Carbon::create(2020, 11),
            'second_semester_start_date' => Carbon::create(2021, 3),
        ]);

        // Create a student linked to the user and session
        Student::create([
            'user_id' => $studentUser->id,
            'school_session_id' => $session->id,
            'matric_no' => StudentController::generateMatricNumber(),
            'current_level' => '100',
            'next_level' => '200',
            'program_type' => Utils::PROGRAM_TYPE_DEGREE,
            'department_id' => $cscDept->id,
        ]);

        // Create a lecturer
        Lecturer::create([
            'user_id' => $lecturerUser->id,
            'department_id' => $cscDept->id,
            'level' => 200,
            'program_type' => Utils::PROGRAM_TYPE_DEGREE,
            'staff_id' => '0001',

        ]);

        AdminUser::create([
            'user_id' => $adminUser->id,
            'staff_id' => Utils::generateStaffId($adminUser->role),
        ]);

        Course::create([
            'name' => 'Introduction to Computer Science',
            'code' => 'CSC111',
            'unit' => 3,
            'level' => 200,
            'semester' => Utils::SEMESTER_FIRST,
            'program_type' => Utils::PROGRAM_TYPE_DEGREE,
            'department_id' => $cscDept->id,
        ]);

        Course::create([
            'name' => 'Introduction to Problem SOlving',
            'code' => 'CSC121',
            'unit' => 3,
            'level' => 200,
            'semester' => Utils::SEMESTER_FIRST,
            'program_type' => Utils::PROGRAM_TYPE_DEGREE,
            'department_id' => $cscDept->id,
        ]);
    }
}
