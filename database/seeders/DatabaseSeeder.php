<?php

namespace Database\Seeders;

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
                "name" => "BIRDI CHAND",
                "email" => null,
                "password" => "04021968",
                "designation" => "ADFM/ACCOUNTS/RAJKOT/WR",
                "department" => "ACCOUNTS",
                "dob" => "04/02/1968",
                "pf_no" => "50816241460",
                "mobile_no" => "9724094102"
            ],
            [
                "name" => "ARYA KIRNENDU KALYANBHAI",
                "email" => null,
                "password" => "23061990",
                "designation" => "SRDFM/ACCOUNTS/RAJKOT/WR",
                "department" => "ACCOUNTS",
                "dob" => "23/06/1990",
                "pf_no" => "50829802940",
                "mobile_no" => "9724094100"
            ],
            [
                "name" => "VASANTLAL PARMAR",
                "email" => null,
                "password" => "21061971",
                "designation" => "ADFM/ACCOUNTS/RAJKOT/WR",
                "department" => "ACCOUNTS",
                "dob" => "21/06/1971",
                "pf_no" => "50820231994",
                "mobile_no" => "9724040286"
            ],
            [
                "name" => "HASMUKH  P   CHUNAWALA",
                "email" => "hasmukh_chunawala@yahoo.com",
                "password" => "27071970",
                "designation" => "APO/PERSONNEL/RAJKOT/WR",
                "department" => "PERSONNEL",
                "dob" => "27/07/1970",
                "pf_no" => "50813623953",
                "mobile_no" => "8355838741"
            ],
            [
                "name" => "SANJEEV KUMAR",
                "email" => null,
                "password" => "05071985",
                "designation" => "AEN/ENGINEERING/JAMNAGAR/WR",
                "department" => "ENGINEERING",
                "dob" => "05/07/1985",
                "pf_no" => "50813832081",
                "mobile_no" => "9724094205"
            ],
            [
                "name" => "ANIL DHANDERWAL",
                "email" => null,
                "password" => "10071988",
                "designation" => "AEE/ELECTRICAL/RAJKOT/WR",
                "department" => "ELECTRICAL",
                "dob" => "10/07/1988",
                "pf_no" => "50813884967",
                "mobile_no" => "9784550903"
            ],
            [
                "name" => "ATUL KUMAR  V  S",
                "email" => null,
                "password" => "12061966",
                "designation" => "AME/MECHANICAL/RAJKOT/WR",
                "department" => "MECHANICAL",
                "dob" => "12/06/1966",
                "pf_no" => "50818086275",
                "mobile_no" => "9909448989"
            ],
            [
                "name" => "S M RAIYARELA",
                "email" => "smraiyarela@gmail.com",
                "password" => "10031967",
                "designation" => "AEN/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "10/03/1967",
                "pf_no" => "50818429853",
                "mobile_no" => "9426925324"
            ],
            [
                "name" => "KAMLESH K DAVE",
                "email" => null,
                "password" => "18071966",
                "designation" => "APO/PERSONNEL/RAJKOT/WR",
                "department" => "PERSONNEL",
                "dob" => "18/07/1966",
                "pf_no" => "50817544464",
                "mobile_no" => "9724094602"
            ],
            [
                "name" => "SUSHILKUMAR  CHAVAN",
                "email" => "Sushilkumar10sc@gmail.com",
                "password" => "18101965",
                "designation" => "ASTE/SnT/SURENDRANAGAR/WR",
                "department" => "SnT",
                "dob" => "18/10/1965",
                "pf_no" => "50813637745",
                "mobile_no" => "7874888641"
            ],
            [
                "name" => "R V SHARMA",
                "email" => "rvsharma1965@yahoo.co.in",
                "password" => "29111965",
                "designation" => "ACMS/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "29/11/1965",
                "pf_no" => "50812345090",
                "mobile_no" => "9773033950"
            ],
            [
                "name" => "RAJKUMAR SHARMA",
                "email" => null,
                "password" => "15031969",
                "designation" => "AEN/ENGINEERING/SURENDRANAGAR/WR",
                "department" => "ENGINEERING",
                "dob" => "15/03/1969",
                "pf_no" => "50817685400",
                "mobile_no" => "7999043858"
            ],
            [
                "name" => "RAJNI YADAV",
                "email" => "rajniyadav15aug@gmail.com",
                "password" => "15081993",
                "designation" => "Sr.DEE/ELECTRICAL/RAJKOT/WR",
                "department" => "ELECTRICAL",
                "dob" => "15/08/1993",
                "pf_no" => "00729800098",
                "mobile_no" => "9650606577"
            ],
            [
                "name" => "MANISH KUMAR DHAKER",
                "email" => null,
                "password" => "25091991",
                "designation" => "DSTE/SnT/RAJKOT/WR",
                "department" => "SnT",
                "dob" => "25/09/1991",
                "pf_no" => "24129801428",
                "mobile_no" => "7381477197"
            ],
            [
                "name" => "HEMANT KUMAR SINGH KADIAN",
                "email" => null,
                "password" => "27111992",
                "designation" => "DOM/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "27/11/1992",
                "pf_no" => "06429801689",
                "mobile_no" => "9811276371"
            ],
            [
                "name" => "KAUSHAL KUMAR CHAUBEY",
                "email" => "dycmms@gmail.com",
                "password" => "24041966",
                "designation" => "ADRM/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "24/04/1966",
                "pf_no" => "39117651712",
                "mobile_no" => null
            ],
            [
                "name" => "DR RAJ KUMAR",
                "email" => null,
                "password" => "30101965",
                "designation" => "CMS/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "30/10/1965",
                "pf_no" => "00108163595",
                "mobile_no" => null
            ],
            [
                "name" => "ASHWANI KUMAR",
                "email" => null,
                "password" => "11061971",
                "designation" => "DRM/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "11/06/1971",
                "pf_no" => "27108459320",
                "mobile_no" => "8800841106"
            ],
            [
                "name" => "NIKHIL SRIVASTAVA",
                "email" => "NIKHIL1629@GMAIL.COM",
                "password" => "04071991",
                "designation" => "Sr.DEN/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "04/07/1991",
                "pf_no" => "00610605204",
                "mobile_no" => "8750091763"
            ],
            [
                "name" => "MAHESHCHAND MEENA",
                "email" => null,
                "password" => "25111985",
                "designation" => "AEN/ENGINEERING/JAMNAGAR/WR",
                "department" => "ENGINEERING",
                "dob" => "25/11/1985",
                "pf_no" => "50881300306",
                "mobile_no" => "9752496103"
            ],
            [
                "name" => "ABHISHEK KUMAR SINGH",
                "email" => null,
                "password" => "20011985",
                "designation" => "Sr. DME/MECHANICAL/RAJKOT/WR",
                "department" => "MECHANICAL",
                "dob" => "20/01/1985",
                "pf_no" => "50800114236",
                "mobile_no" => "9724040001"
            ],
            [
                "name" => "RAKESH KUMAR SINGH",
                "email" => null,
                "password" => "04031982",
                "designation" => "ASTE/GEN. ADMN./JAMNAGAR/WR",
                "department" => "GEN. ADMN.",
                "dob" => "04/03/1982",
                "pf_no" => "50819131099",
                "mobile_no" => "7878302050"
            ],
            [
                "name" => "J.H.DAMOR.",
                "email" => "jhdamor1966@gamil.com",
                "password" => "05031966",
                "designation" => "Sr. DMM/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "05/03/1966",
                "pf_no" => "50817651475",
                "mobile_no" => "9727299730"
            ],
            [
                "name" => "SUNIL KUMAR MEENA",
                "email" => null,
                "password" => "05021984",
                "designation" => "Sr. DCM/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "05/02/1984",
                "pf_no" => "50880815741",
                "mobile_no" => "7340069299"
            ],
            [
                "name" => "RAMESH CHAND MEENA",
                "email" => null,
                "password" => "27091984",
                "designation" => "Sr. DSO/OPERATING/RAJKOT/WR",
                "department" => "OPERATING",
                "dob" => "27/09/1984",
                "pf_no" => "508NP001304",
                "mobile_no" => "7838984394"
            ],
            [
                "name" => "PANKAJKUMAR JHA",
                "email" => "pankajjha249 @g mail.com",
                "password" => "12021980",
                "designation" => "PS/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "12/02/1980",
                "pf_no" => "50813768980",
                "mobile_no" => "9969180480"
            ],
            [
                "name" => "PALLVI D CHRISTAIN",
                "email" => null,
                "password" => "16031969",
                "designation" => "ANO/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "16/03/1969",
                "pf_no" => "50814226236",
                "mobile_no" => "9724093536"
            ],
            [
                "name" => "MANNE SAMUDRA",
                "email" => "samudramanne@gmail.com",
                "password" => "18061983",
                "designation" => "DMO/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "18/06/1983",
                "pf_no" => "50829804147",
                "mobile_no" => "8019756415"
            ],
            [
                "name" => "AMRUT U SOLANKI",
                "email" => "solankiau25@yahoo.in",
                "password" => "25081967",
                "designation" => "DPO/PERSONNEL/RAJKOT/WR",
                "department" => "PERSONNEL",
                "dob" => "25/08/1967",
                "pf_no" => "50818668926",
                "mobile_no" => "9426532362"
            ],
            [
                "name" => "SUNILKUMAR H.GUPTA",
                "email" => null,
                "password" => "12031976",
                "designation" => "Sr. DOM/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "12/03/1976",
                "pf_no" => "50813638774",
                "mobile_no" => "9765016600"
            ],
            [
                "name" => "DURGESHKUMAR S",
                "email" => null,
                "password" => "10111973",
                "designation" => "ASTE/SnT/RAJKOT/WR",
                "department" => "SnT",
                "dob" => "10/11/1973",
                "pf_no" => "50814499850",
                "mobile_no" => "6353416935"
            ],
            [
                "name" => "RAMESHBHAI BABUBHAI PANDOR",
                "email" => null,
                "password" => "19091970",
                "designation" => "ACM/COMMERCIAL/RAJKOT/WR",
                "department" => "COMMERCIAL",
                "dob" => "19/09/1970",
                "pf_no" => "50814638976",
                "mobile_no" => "9879362010"
            ],
            [
                "name" => "NARESH P DANGI",
                "email" => null,
                "password" => "16051965",
                "designation" => "ADSO/OPERATING/RAJKOT/WR",
                "department" => "OPERATING",
                "dob" => "16/05/1965",
                "pf_no" => "50817435870",
                "mobile_no" => "9429209865"
            ],
            [
                "name" => "S R DUBE",
                "email" => null,
                "password" => "26061967",
                "designation" => "CPM/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "26/06/1967",
                "pf_no" => "50814579017",
                "mobile_no" => "9892713501"
            ],
            [
                "name" => "KIRITKUMAR MOHANLAL PARMAR",
                "email" => "k.m.parmar.sew@gmail.com",
                "password" => "01061965",
                "designation" => "AEN/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "01/06/1965",
                "pf_no" => "50818431940",
                "mobile_no" => "9340075398"
            ],
            [
                "name" => "PUSHKAR JATINKUMAR RAMESHWAR",
                "email" => null,
                "password" => "11081984",
                "designation" => "AOM/OPERATING/RAJKOT/WR",
                "department" => "OPERATING",
                "dob" => "11/08/1984",
                "pf_no" => "50813871899",
                "mobile_no" => "9016077014"
            ],
            [
                "name" => "NARESHKUMAR VINODCHAND SHARMA",
                "email" => "nvsharma1965@gmail.com",
                "password" => "15101965",
                "designation" => "AOM/OPERATING/RAJKOT/WR",
                "department" => "OPERATING",
                "dob" => "15/10/1965",
                "pf_no" => "50817991444",
                "mobile_no" => "9727984507"
            ],
            [
                "name" => "H R JOSHI",
                "email" => null,
                "password" => "29091967",
                "designation" => "COML. INSP./COMMERCIAL/RAJKOT/WR",
                "department" => "COMMERCIAL",
                "dob" => "29/09/1967",
                "pf_no" => "50817997434",
                "mobile_no" => "8980011008"
            ],
            [
                "name" => "HARI SHANKAR ARYA",
                "email" => null,
                "password" => "06041981",
                "designation" => "Sr. DSTE/SnT/RAJKOT/WR",
                "department" => "SnT",
                "dob" => "06/04/1981",
                "pf_no" => "50714404017",
                "mobile_no" => "7908120759"
            ],
            [
                "name" => "ATUL TRIPATHI",
                "email" => "atultripathi1969@gmail.com",
                "password" => "22111969",
                "designation" => "SR.PUBLIC PROSECUTOR/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "22/11/1969",
                "pf_no" => "53300700022",
                "mobile_no" => "9274687536"
            ],
            [
                "name" => "M L MEENA",
                "email" => null,
                "password" => "01071990",
                "designation" => "Sr.DEE/ELECTRICAL/RAJKOT/WR",
                "department" => "ELECTRICAL",
                "dob" => "01/07/1990",
                "pf_no" => "508NPS00728",
                "mobile_no" => "7709161583"
            ],
            [
                "name" => "GIRIJA SHANKAR AHIRWAR",
                "email" => null,
                "password" => "01021966",
                "designation" => "AEE/ELECTRICAL/RAJKOT/WR",
                "department" => "ELECTRICAL",
                "dob" => "01/02/1966",
                "pf_no" => "50819516769",
                "mobile_no" => "9421419410"
            ],
            [
                "name" => "RAMESHCHAND J MEENA",
                "email" => null,
                "password" => "04031968",
                "designation" => "AEN/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "04/03/1968",
                "pf_no" => "50820822455",
                "mobile_no" => "9428168341"
            ],
            [
                "name" => "BAPAT SUHAS GAJANAN",
                "email" => "suhasbapat7319@gmail.com",
                "password" => "15061973",
                "designation" => "AEE/GEN. ADMN./RAJKOT/WR",
                "department" => "GEN. ADMN.",
                "dob" => "15/06/1973",
                "pf_no" => "50813629037",
                "mobile_no" => null
            ],
            [
                "name" => "PRANAV YOGESHKUMAR MINIPARA",
                "email" => "pranavminipara512@gmail.com",
                "password" => "05121997",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "05/12/1997",
                "pf_no" => "22729805622",
                "mobile_no" => "9426247432"
            ],
            [
                "name" => "RAJGOR MAHESHKUMAR JAYANTIBHAI",
                "email" => "MICHURAJGOR36@GMAIL.COM",
                "password" => "09041999",
                "designation" => "ADMO(PROB)/GEN. ADMN./OKHA/WR",
                "department" => "GEN. ADMN.",
                "dob" => "09/04/1999",
                "pf_no" => "22729805758",
                "mobile_no" => "7698371810"
            ],
            [
                "name" => "NARENDRA SINGH",
                "email" => null,
                "password" => "30031974",
                "designation" => "Sr. DEN/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "30/03/1974",
                "pf_no" => "50825825641",
                "mobile_no" => "7567101588"
            ],
            [
                "name" => "GAURAV",
                "email" => null,
                "password" => "10101995",
                "designation" => "ADEN/ENGINEERING/WANKANER/WR",
                "department" => "ENGINEERING",
                "dob" => "10/10/1995",
                "pf_no" => "21729805372",
                "mobile_no" => "8210089507"
            ],
            [
                "name" => "KRISHNA NAIK BHUKE",
                "email" => null,
                "password" => "15051988",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "15/05/1988",
                "pf_no" => "22729805315",
                "mobile_no" => "8985751067"
            ],
            [
                "name" => "RISHABH SINGH CHAUHAN",
                "email" => null,
                "password" => "17101995",
                "designation" => "DEN/ENGINEERING/RAJKOT/WR",
                "department" => "ENGINEERING",
                "dob" => "17/10/1995",
                "pf_no" => "00629802718",
                "mobile_no" => "9711546798"
            ],
            [
                "name" => "NEETHU S",
                "email" => "NEETHUSIVADASKARUVANKOVIL@GMAIL.COM",
                "password" => "19111990",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "19/11/1990",
                "pf_no" => "22729805486",
                "mobile_no" => "9495559648"
            ],
            [
                "name" => "MOHAMMED AFSAL P",
                "email" => "ASFL333@GMAIL.COM",
                "password" => "25051990",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "25/05/1990",
                "pf_no" => "22729805485",
                "mobile_no" => "9946498219"
            ],
            [
                "name" => "RAYEESA K K",
                "email" => "RAYEESAKK92@GMAIL.COM",
                "password" => "21091992",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "21/09/1992",
                "pf_no" => "22729805495",
                "mobile_no" => "9489694706"
            ],
            [
                "name" => "SREEKANTH R",
                "email" => null,
                "password" => "02061987",
                "designation" => "DMO/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "02/06/1987",
                "pf_no" => "22729804757",
                "mobile_no" => "9447753409"
            ],
            [
                "name" => "ARUN PRAGADISH RAM R M",
                "email" => "DRAPR1991@GMAIL.COM",
                "password" => "28061991",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "28/06/1991",
                "pf_no" => "22729805476",
                "mobile_no" => "8903702077"
            ],
            [
                "name" => "SASIKUMAR M",
                "email" => "SASIMGRS@PROTONMAIL.COM",
                "password" => "02061994",
                "designation" => "ADMO(PROB)/MEDICAL/OKHA/WR",
                "department" => "MEDICAL",
                "dob" => "02/06/1994",
                "pf_no" => "22729805441",
                "mobile_no" => "8508175855"
            ],
            [
                "name" => "SABAPARA DWEEP MANISHBHAI",
                "email" => null,
                "password" => "08071996",
                "designation" => "IRSMEP/MECHANICAL/HAPA/WR",
                "department" => "MECHANICAL",
                "dob" => "08/07/1996",
                "pf_no" => "03829801811",
                "mobile_no" => "9979973746"
            ],
            [
                "name" => "DEEPIKA S S",
                "email" => "SSDEEPIKA01@GMAIL.COM",
                "password" => "31071993",
                "designation" => "ADMO(PROB)/MEDICAL/RAJKOT/WR",
                "department" => "MEDICAL",
                "dob" => "31/07/1993",
                "pf_no" => "22729805823",
                "mobile_no" => "8590135703"
            ],
            [
                "name" => "DHARMENDR GURYANI",
                "email" => null,
                "password" => "16011969",
                "designation" => "ASC/SECURITY/RAJKOT/WR",
                "department" => "SECURITY",
                "dob" => "16/01/1969",
                "pf_no" => "00210632025",
                "mobile_no" => "7002398524"
            ],
            [
                "name" => "KAMLESHWAR SINGH",
                "email" => null,
                "password" => "08111966",
                "designation" => "DSC/SECURITY/RAJKOT/WR",
                "department" => "SECURITY",
                "dob" => "08/11/1966",
                "pf_no" => "51200013558",
                "mobile_no" => "9724094700"
            ]
        ]);

        $departments = Department::insert($departments);
        $departments = Department::all()->keyBy('name');
        $usersThatNeedToBePushed = collect();

        $users->each(function ($user) use ($usersThatNeedToBePushed, $departments) {
            $user['department_id'] = $departments[$user['department']]->id;
            unset($user['department']);
            $user['email'] = $user['email'] ?? fake()->unique()->safeEmail;
            $user['password'] = bcrypt($user['password']);
            $user['name'] = ucwords(strtolower($user['name']));
            $usersThatNeedToBePushed->push($user);
        });

        User::insert($usersThatNeedToBePushed->toArray());

    }
}
