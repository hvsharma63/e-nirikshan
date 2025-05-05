<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Jobs\SendWelcomeEmail;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ["name" => "ACCOUNTS"],
            ["name" => "PERSONNEL"],
            ["name" => "ENGINEERING"],
            ["name" => "ELECTRICAL"],
            ["name" => "MECHANICAL"],
            ["name" => "SnT"],
            ["name" => "MEDICAL"],
            ["name" => "GEN. ADMN."],
            ["name" => "OPERATING"],
            ["name" => "COMMERCIAL"],
            ["name" => "SECURITY"],
        ];

        $users = collect([
            [
                "name" => "Rajni Yadav",
                "email" => "rajniy0893@gmail.com",
                "password" => "15081993",
                "designation" => "Sr.DEE/ELEC/RJT/WR",
                "department" => "ELECTRICAL",
                "dob" => "15/08/1993",
                "pf_no" => "00729800098",
                "mobile_no" => "9724094300",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "name" => "RAJGOR MAHESHKUMAR JAYANTIBHAI",
                "email" => "michurajgor36@gmail.com",
                "password" => "09041999",
                "designation" => "ADMO/MED/RJT/WR",
                "department" => "MEDICAL",
                "dob" => "09/04/1999",
                "pf_no" => "22729805758",
                "mobile_no" => "7698371810",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "name" => "SUNILKUMAR H.GUPTA",
                "email" => "sunielg1@gmail.com",
                "password" => "12031976",
                "designation" => "Sr.DOM/OPERATING/RJT/WR",
                "department" => "OPERATING",
                "dob" => "12/03/1976",
                "pf_no" => "50813638774",
                "mobile_no" => "9724094900",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "name" => "DWEEP SABAPARA",
                "email" => "rjtadme1@gmail.com",
                "password" => "08071996",
                "designation" => "ADME/MECH/JAM/WR",
                "department" => "MECHANICAL",
                "dob" => "08/07/1996",
                "pf_no" => "03829801811",
                "mobile_no" => "9724094401",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
            [
                "name" => "RAKESH KUMAR SINGH",
                "email" => "rakeshkumar41154@gmail.com",
                "password" => "04031982",
                "designation" => "ADSTE/SnT/JAM/WR",
                "department" => "SnT",
                "dob" => "04/03/1982",
                "pf_no" => "50819131099",
                "mobile_no" => "9724094827",
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ],
        ]);

        $departments = Department::insert($departments);
        $departments = Department::all()->keyBy('name');
        $usersThatNeedToBePushed = collect();

        $users->each(function ($user) use ($usersThatNeedToBePushed, $departments) {
            $user['department_id'] = $departments[$user['department']]->id;
            unset($user['department']);
            $user['password'] = bcrypt($user['password']);
            $user['name'] = ucwords(strtolower($user['name']));
            $usersThatNeedToBePushed->push($user);
        });

        User::insert($usersThatNeedToBePushed->toArray());

        // Send welcome emails after insertion using actual User models
        $createdUsers = User::whereIn('email', $usersThatNeedToBePushed->pluck('email'))->get();
        foreach ($createdUsers as $user) {
            SendWelcomeEmail::dispatch($user);
        }
    }
}
