<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountCode;
use Carbon\Carbon;

class DiscountCodesTableSeeder extends Seeder
{
    public function run(): void
    {
        $discountCodes = [
            [
                'code' => 'WELCOME10',
                'discount_amount' => 10.00,
                'discount_type' => 'percentage',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(1),
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER2024',
                'discount_amount' => 15.00,
                'discount_type' => 'percentage',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'FIXED5',
                'discount_amount' => 5.00,
                'discount_type' => 'fixed',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ],
        ];

        foreach ($discountCodes as $discountCode) {
            DiscountCode::create($discountCode);
        }
    }
}