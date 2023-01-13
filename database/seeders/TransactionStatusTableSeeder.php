<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('transaction_statuses')->delete();

        \DB::table('transaction_statuses')->insert([
            [
                'id' => 1,
                'status' => 'Pending',
            ], [
                'id' => 2,
                'status' => 'Escrowed'
            ], [
                'id' => 3,
                'status' => 'Approved by Admin'
            ], [
                'id' => 4,
                'status' => 'Rejected by Admin'
            ], [
                'id' => 5,
                'status' => 'Cancelled'
            ], [
                'id' => 6,
                'status' => 'Transfered to Seller'
            ], [
                'id' => 7,
                'status' => 'Completed'
            ], [
                'id' => 8,
                'status' => 'In-Dispute'
            ], [
                'id' => 9,
                'status' => 'Dispute Finished'
            ]
        ]);
    }
}
