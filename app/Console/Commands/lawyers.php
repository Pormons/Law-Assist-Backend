<?php

namespace App\Console\Commands;

use App\Models\Lawyer;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;


class lawyers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lawyers';

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
        
        $users = (new FastExcel)->import('storage/app/public/Lawyers.xlsx', function ($line) {
            return Lawyer::create([
                'fullname' => $line['Full Name'],
                'address' => $line['Address'],
                'roll_signed_date' => $line['Roll Signed Date'],
                'roll_number' => $line['Roll number'],
                'phone_number' => $line['Phone Number'],
                'email' => $line['Email']
            ]);
        });

        return $users;
    }
}
