<?php

namespace Database\Seeders;

use App\Models\LoanPolicy;
use Illuminate\Database\Seeder;

class LoanPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (LoanPolicy::DEFAULT_POLICIES as $grade => $policy) {
            LoanPolicy::updateOrCreate(
                ['grade' => $grade],
                $policy
            );
        }
    }
}
