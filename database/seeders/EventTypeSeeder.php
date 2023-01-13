<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('event_types')->delete();

        \DB::table('event_types')->insert([
            [
                'id' => 1,
                'event_name' => 'Admin Login',
            ],
            [
                'id' => 2,
                'event_name' => 'Admin Logout',
            ],
            [
                'id' => 3,
                'event_name' => 'Admin Profile Update',
            ],
            [
                'id' => 4,
                'event_name' => 'Admin Site Setting Update',
            ],
            [
                'id' => 5,
                'event_name' => 'Admin Escrow Setting Update',
            ],
            [
                'id' => 6,
                'event_name' => 'Admin User Add',
            ],
            [
                'id' => 7,
                'event_name' => 'Admin User Update',
            ],
            [
                'id' => 8,
                'event_name' => 'Admin User Delete',
            ],
            [
                'id' => 9,
                'event_name' => 'Admin User Status Change',
            ],
            [
                'id' => 10,
                'event_name' => 'Buyer Add',
            ],
            [
                'id' => 11,
                'event_name' => 'Buyer Update',
            ],
            [
                'id' => 12,
                'event_name' => 'Buyer Delete',
            ],
            [
                'id' => 13,
                'event_name' => 'Buyer Status Change',
            ],
            [
                'id' => 14,
                'event_name' => 'Seller Add',
            ],
            [
                'id' => 15,
                'event_name' => 'Seller Update',
            ],
            [
                'id' => 16,
                'event_name' => 'Seller Delete',
            ],
            [
                'id' => 17,
                'event_name' => 'Seller Status Change',
            ],
            [
                'id' => 18,
                'event_name' => 'Seller ETL Status Change',
            ],
            [
                'id' => 19,
                'event_name' => 'CMS Page Add',
            ],
            [
                'id' => 20,
                'event_name' => 'CMS Page Update',
            ],
            [
                'id' => 21,
                'event_name' => 'CMS Page Delete',
            ],
            [
                'id' => 22,
                'event_name' => 'CMS Page Status Change',
            ],
            [
                'id' => 23,
                'event_name' => 'Contact Us Log Delete',
            ],
            [
                'id' => 24,
                'event_name' => 'Contact Us Log Replied',
            ],
            [
                'id' => 25,
                'event_name' => 'Email Template Add',
            ],
            [
                'id' => 26,
                'event_name' => 'Email Template Update',
            ],
            [
                'id' => 27,
                'event_name' => 'Email Template Delete',
            ],
            [
                'id' => 28,
                'event_name' => 'Email Template Status Change',
            ],
            /** ESCROW */
            [
                'id' => 29,
                'event_name' => 'Admin Escrow Message Sent',
            ],
            [
                'id' => 30,
                'event_name' => 'Admin Reject Transaction',
            ],
            [
                'id' => 31,
                'event_name' => 'Admin Cancel Escrow',
            ],
            [
                'id' => 32,
                'event_name' => 'Admin Transfered to Seller',
            ],
            [
                'id' => 33,
                'event_name' => 'Admin Send Dispute Message',
            ],
            [
                'id' => 34,
                'event_name' => 'Admin Finish Dispute',
            ],

            /**
             * Front Side Events as notificaions
             */
            // [
            //     'id' => 35,
            //     'event_name' => 'Buyer Send Message',
            // ],
            // [
            //     'id' => 36,
            //     'event_name' => 'Seller Send Message',
            // ],
            // [
            //     'id' => 37,
            //     'event_name' => 'Buyer Send Private Message',
            // ],
            // [
            //     'id' => 38,
            //     'event_name' => 'Seller Send Private Message',
            // ],
            // [
            //     'id' => 39,
            //     'event_name' => 'Buyer Send Dispute Message',
            // ],
            // [
            //     'id' => 40,
            //     'event_name' => 'Seller Send Dispute Message',
            // ],






        ]);
    }
}
