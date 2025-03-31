<?php

namespace Database\Seeders;

use App\Jobs\SendWelcomeEmail;
use App\Models\Department;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

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
                "name" => "ARYA KIRNENDU KALYANBHAI",
                "email" => "rjtsrdfm@gmail.com",
                "password" => "23061990",
                "designation" => "SRDFM/ACCOUNTS/RJT/WR",
                "department" => "ACCOUNTS",
                "dob" => "23/06/1990",
                "pf_no" => "50829802940",
                "mobile_no" => "9724094100"
            ],
            [
                "name" => "NARESHKUMAR VINODCHAND SHARMA",
                "email" => "nvsharma1965@gmail.com",
                "password" => "15101965",
                "designation" => "AOM/OPERATING/RJT/WR",
                "department" => "OPERATING",
                "dob" => "15/10/1965",
                "pf_no" => "50817991444",
                "mobile_no" => "9724094904"
            ],
            [
                "name" => "GIRJA SHANKAR AHIRWAR",
                "email" => "adeetrdrjt@gmail.com",
                "password" => "01021966",
                "designation" => "ADEE/TRD/RJT/WR",
                "department" => "ELECTRICAL",
                "dob" => "01/02/1966",
                "pf_no" => "50819516769",
                "mobile_no" => "9724094305"
            ],
            [
                "name" => "ANIL DHANDERWAL",
                "email" => "dhanderwalanil@gmail.com",
                "password" => "10071988",
                "designation" => "ADEE/ELECTRICAL/RJT/WR",
                "department" => "ELECTRICAL",
                "dob" => "10/07/1988",
                "pf_no" => "50813884967",
                "mobile_no" => "9724094314"
            ],
            [
                "name" => "KAMLESH K DAVE",
                "email" => "kkdave123@gmail.com",
                "password" => "18071966",
                "designation" => "APO/PERSONNEL/RJT/WR",
                "department" => "PERSONNEL",
                "dob" => "18/07/1966",
                "pf_no" => "50817544464",
                "mobile_no" => "9724094602"
            ],
            [
                "name" => "J. H. DAMOR.",
                "email" => "srdmmrjt@gmail.com",
                "password" => "05031966",
                "designation" => "Sr.DMM/GEN.ADMN./RJT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "05/03/1966",
                "pf_no" => "50817651475",
                "mobile_no" => "9724094770"
            ],
            [
                "name" => "KRISHNA NAIK BHUKE",
                "email" => "krishnanaikbukke@gmail.com",
                "password" => "15051988",
                "designation" => "DMO/MEDICAL/RJT/WR",
                "department" => "MEDICAL",
                "dob" => "15/05/1988",
                "pf_no" => "22729805315",
                "mobile_no" => "8985751067"
            ],
            [
                "name" => "KIRITKUMAR MOHANLAL PARMAR",
                "email" => "adenrjt@gmail.com",
                "password" => "01061965",
                "designation" => "ADEN/ENGINEERING/RJT/WR",
                "department" => "ENGINEERING",
                "dob" => "01/06/1965",
                "pf_no" => "50818431940",
                "mobile_no" => "9724094206"
            ],
            [
                "name" => "HASMUKH CHUNAWALA",
                "email" => "hasmukh_chunawala@yahoo.com",
                "password" => "27071970",
                "designation" => "APO/PERSONNEL/RJT/WR",
                "department" => "PERSONNEL",
                "dob" => "27/07/1970",
                "pf_no" => "50813623953",
                "mobile_no" => "9724094603"
            ],
            [
                "name" => "SUNIL KUMAR MEENA",
                "email" => "srdcmrjt@gmail.com",
                "password" => "05021984",
                "designation" => "Sr.DCM/GEN.ADMN./RJT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "05/02/1984",
                "pf_no" => "50880815741",
                "mobile_no" => "9724094950"
            ],
            [
                "name" => "GAURAV",
                "email" => "adenwkr@gmail.com",
                "password" => "10101995",
                "designation" => "ADEN/ENGINEERING/WKR/WR",
                "department" => "ENGINEERING",
                "dob" => "10/10/1995",
                "pf_no" => "21729805372",
                "mobile_no" => "9724094258"
            ],
            [
                "name" => "M L MEENA",
                "email" => "srdeetrdrjt@gmail.com",
                "password" => "01071990",
                "designation" => "Sr.DEE/ELECTRICAL/RJT/WR",
                "department" => "ELECTRICAL",
                "dob" => "01/07/1990",
                "pf_no" => "508NPS00728",
                "mobile_no" => "9724094303"
            ],
            [
                "name" => "MEGHRAJ TATER",
                "email" => "meghrajtater2005@gmail.com",
                "password" => "05071991",
                "designation" => "Sr.DME/MECHANICAL/RJT/WR",
                "department" => "MECHANICAL",
                "dob" => "05/07/1991",
                "pf_no" => "03829800091",
                "mobile_no" => "9724094400"
            ],
            [
                "name" => "SANJEEV KUMAR",
                "email" => "adenwjamrjt@gmail.com",
                "password" => "05071985",
                "designation" => "ADEN/ENGINEERING/JAM/WR",
                "department" => "ENGINEERING",
                "dob" => "05/07/1985",
                "pf_no" => "50813832081",
                "mobile_no" => "9724094205"
            ],
            [
                "name" => "NARENDRA SINGH",
                "email" => "narendra.nsp38@gmail.com",
                "password" => "30031974",
                "designation" => "Sr.DEN/ENGINEERING/RJT/WR",
                "department" => "ENGINEERING",
                "dob" => "30/03/1974",
                "pf_no" => "50825825641",
                "mobile_no" => "9724094200"
            ],
            [
                "name" => "MAHESH CHAND MEENA",
                "email" => "adenejamrjt@gmail.com",
                "password" => "25111985",
                "designation" => "ADEN/ENGINEERING/JAM/WR",
                "department" => "ENGINEERING",
                "dob" => "25/11/1985",
                "pf_no" => "50881300306",
                "mobile_no" => "9724094212"
            ],
            [
                "name" => "RAMESHBHAI BABUBHAI PANDOR",
                "email" => "rbpandor.commercial@gmail.com",
                "password" => "19091970",
                "designation" => "ACM/COMMERCIAL/RJT/WR",
                "department" => "COMMERCIAL",
                "dob" => "19/09/1970",
                "pf_no" => "50814638976",
                "mobile_no" => "9724094952"
            ],
            [
                "name" => "NARESH P DANGI",
                "email" => "dangi_naresh@yahoo.com",
                "password" => "16051965",
                "designation" => "ADSO/OPERATING/RJT/WR",
                "department" => "OPERATING",
                "dob" => "16/05/1965",
                "pf_no" => "50817435870",
                "mobile_no" => "9724094714"
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
