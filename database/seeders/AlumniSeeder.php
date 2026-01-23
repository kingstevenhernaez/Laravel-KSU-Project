namespace Database\Seeders;

public function run(): void
{
    \DB::table('alumni')->insert([
        [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'graduation_year' => 2023,
            'course' => 'BS Information Technology',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'graduation_year' => 2022,
            'course' => 'BS Computer Engineering',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}