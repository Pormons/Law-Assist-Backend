<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class LawyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LazyCollection::make(function () {
            $handle = fopen(public_path("Lawyers.csv"), 'r');
            
            while (($line = fgetcsv($handle, 4096)) !== false) {
              $dataString = implode(", ", $line);
              $row = explode(';', $dataString);
              yield $row;
            }
      
            fclose($handle);
          })
          ->skip(1)
          ->chunk(1000)
          ->each(function (LazyCollection $chunk) {
            $records = $chunk->map(function ($row) {
              return [
                  "fullname" => $row[0],
                  "address" => $row[1],
                  "roll_signed_date" => $row[2],
                  "roll_number" => $row[3],
                  "phone_number" => $row[4],
                  "email" => $row[5],
              ];
            })->toArray();
            
            DB::table('lawyer')->insert($records);
          });
    }
}
