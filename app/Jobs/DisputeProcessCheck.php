<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DisputeProcessCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dispute_data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->dispute_data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product_id = $this->dispute_data['product_id'];
        if ($this->dispute_data['level'] == 1) {
            $dispute_transaction = \DB::table('dispute_transaction')->where('product_id', $product_id)->orderByDesc('id')->first();

            if ($dispute_transaction && $dispute_transaction->user_id == $this->dispute_data['user_id'] && $dispute_transaction->level == 1) {
                // dispute resolved
                // \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['status' => 1]);
            }
        }
        // if ($this->dispute_data['level'] == 3) {
        //     \DB::table('dispute_transaction')->where('product_id', $product_id)->update(['level' => 3,'offer_expire_time' => Carbon::now()]);
        // }
    }
}
