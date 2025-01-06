<?php

namespace Database\Seeders;

use App\Models\UserApproval;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_approval = [
            [
                'user_id' => 2,
                'ttd_path' => 1,
            ],
            [
                'user_id' => 3,
                'ttd_path' => 1,
            ],
            [
                'user_id' => 4,
                'ttd_path' => 1,
            ],
        ];

        foreach ($user_approval as $approval) {
            UserApproval::create($approval);
        }
    }
}
