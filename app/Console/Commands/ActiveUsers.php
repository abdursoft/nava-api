<?php

namespace App\Console\Commands;

use App\Models\ActiveStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;


class ActiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:active-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $person = ActiveStatus::all();
        foreach($person as $item){
            $time = Carbon::parse($item->updated_at)->diffInMinute(now());
            if( round($time) >= 3){
                $ago = Carbon::parse($item->updated_at)->diffForHumans();
                $item->update(['status' => 'inactive', 'active_note' => $ago]);
            }
        }
    }
}
