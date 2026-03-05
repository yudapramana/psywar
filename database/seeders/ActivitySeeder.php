<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Activity;
use App\Models\ActivityTopic;
use App\Models\ActivitySpeaker;
use App\Models\ActivityPanelist;
use App\Models\ActivitySponsor;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('activity_sponsors')->truncate();
        DB::table('activity_panelists')->truncate();
        DB::table('activity_speakers')->truncate();
        DB::table('activity_topics')->truncate();
        DB::table('activities')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $eventId = Event::first()->id;

        /**
         * =====================================================
         * SYMPOSIUM + TOPICS (SESUI PROPOSAL)
         * =====================================================
         */
        $symposia = [
            1 => [
                'title' => 'The Evolving Landscape of ACS Care: Evidence and Experience',
                'moderator' => 'dr. Sari Haryati, Sp.JP',
                'speakers' => [
                    'dr. M. Syukri, Sp.JP (K)',
                    'dr. Ivan Mahendra Raditya, Sp.JP (K)'
                ],
                'sponsors' => [
                    ['name'=>'Kimia Farma','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613184/PandanViewMandeh/oavjextthdfaipzfwmop.png"],
                    ['name'=>'AstraZeneca','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620250/PandanViewMandeh/q6d3wr30vfwyb6mxj1qw.png"]
                ],
                'topics' => [
                    ['title'=>'Walking the Tightrope: Balancing Antithrombotic Potency and Bleeding Risks in ACS','type'=>'lecture'],
                    ['title'=>'Pain Relief Through Ischemia Control in Acute Coronary Syndrome','type'=>'lecture'],
                    ['title'=>'Discussion','type'=>'discussion'],
                ]
            ],

            2 => [
                'title' => 'Case in the Box: Managing High Calcified Lesion in Acute Coronary Syndrome',
                'lecture' => 'dr. M. Syukri, Sp.JP (K)',
                'case_presenter' => 'dr. M. Fakhri, Sp.JP',
                'panelists' => [
                    'dr. M. Syukri, Sp.JP (K)',
                    'dr. Masrul, Sp.JP (K)',
                    'dr. Trian Faesa, Sp.JP (K)',
                    'dr. Isral, Sp.JP (K)',
                    'dr. Deddy Kurniawan Jahja, Sp.JP (K)'
                ],
                'sponsors' => [
                    ['name'=>'Revass','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613492/PandanViewMandeh/jexct0sqcxkqdelvwphw.png"],
                    ['name'=>'Darya Varia','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613542/PandanViewMandeh/li2vwpkrxphjy2xbq53c.svg"],
                    ['name'=>'Bayer','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613581/PandanViewMandeh/pgsdyaqs3rkhqyix7jpi.svg"]
                ],
                'topics' => [
                    ['title'=>'Before the Stent Hits the Landing Station: Getting ACS Right from the Start','type'=>'lecture'],
                    ['title'=>'When Plaque Turns to Stone: Role of Intracoronary Imaging and Atherectomy','type'=>'video']
                ]
            ],

            3 => [
                'title' => 'Case in the Box: Beyond the Irregular Rhythm: Anticoagulant on Board through Pulse Field Ablation',
                'lecture'=>'dr. Hauda El Rasyid, Sp.JP (K)',
                'case_presenter'=>'Dr. dr. Tommy Daindes, Sp.JP (K)',
                'panelists'=>[
                    'dr. Hauda Sp.JP (K)',
                    'dr. Haris G, Sp.JP',
                    'dr. Putri Mardhatilla, Sp.JP',
                    'dr. Hadi Syam, Sp.JP'
                ],
                'sponsors'=>[
                    ['name'=>'Ferron','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613641/PandanViewMandeh/q6tts6mulinpk8lg3fm1.png"]
                ],
                'topics'=>[
                    ['title'=>'The Continuum of Protection: DOAC Strategy from Clinic and Post Ablation','type'=>'lecture'],
                    ['title'=>'Breaking the Chaotic Rhythm—Mapping and Delivering Curative Ablation','type'=>'video']
                ]
            ],

            4 => [
                'title'=>'Redefining Heart Failure Management: Beyond Ejection Fraction',
                'lecture'=>'Dr. dr. Mefri Yanni, Sp.JP (K)',
                'case_presenter'=>'dr. Putri Handayani, Sp.JP',
                'sponsors'=>[
                    ['name'=>'Novartis','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613692/PandanViewMandeh/oxq7sese72ajlh7fjfhj.png"]
                ],
                'panelists'=>[
                    'dr. Mefri Yanni, Sp.JP (K)',
                    'dr. Andy Rahman, Sp.JP',
                    'dr. Tommy Daindes, Sp.JP (K)',
                    'dr. Rita Hamdani, Sp.JP'
                ],
                'topics'=>[
                    ['title'=>'Beyond Ejection Fraction: Redefining HF as a Progressive Disease','type'=>'lecture'],
                    ['title'=>'When Normal EF Is Not Enough: Case Illustration of Progressive HF','type'=>'case']
                ]
            ],

            5 => [
                'title'=>'Daily Practice and First-Line Antihypertensive Therapy: What, When, and Why?',
                'moderator'=>'dr. Finesa Adyatessa Hasye, Sp.JP',
                'speakers'=>[
                    'dr. Nani, Sp.JP (K)',
                    'dr. Kino, Sp.JP (K)'
                ],
                'sponsors'=>[
                    ['name'=>'Tanabe','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613815/PandanViewMandeh/eo37cmwnbioe0rplrwnp.jpg"],
                    ['name'=>'Ferron','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613641/PandanViewMandeh/q6tts6mulinpk8lg3fm1.png"]
                ],
                'topics'=>[
                    ['title'=>'Modern Antihypertensive Therapy: Choosing the Right Drug','type'=>'lecture'],
                    ['title'=>'Combination Therapy as First-Line Treatment','type'=>'lecture'],
                    ['title'=>'Discussion','type'=>'discussion']
                ]
            ],

            6 => [
                'title'=>'Debate Session: Puncture or Slice – Optimal Myocardial Revascularization',
                'moderator'=>'dr. Tia Febrianti, Sp.JP',
                'speakers'=>[
                    'dr. Aulia Rahman, Sp.B TKV',
                    'dr. Dede Jumatri Tito, Sp.JP (K)'
                ],
                'topics'=>[
                    ['title'=>'Case Presentation: Puncture or Slice Strategy','type'=>'case'],
                    ['title'=>'Debate Session','type'=>'discussion']
                ]
            ],

            7 => [
                'title'=>'Prevention: Residual Cardiovascular Risk After LDL Control',
                'moderator'=>'dr. Meidianaser, Sp.JP',
                'speakers'=>[
                    'dr. Rita Hamdani, Sp.JP (K)',
                    'dr. Citra Kiki Krevani, Sp.JP (K)'
                ],
                'sponsors'=>[
                    ['name'=>'AstraZeneca','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620250/PandanViewMandeh/q6d3wr30vfwyb6mxj1qw.png"],
                    ['name'=>'Meiji','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613921/PandanViewMandeh/gqfi2pgwypiw38h9fufd.png"]
                ],
                'topics'=>[
                    ['title'=>'Beyond Cholesterol Numbers: Lipid Lowering and Outcomes','type'=>'lecture'],
                    ['title'=>'Two Pathways, One Goal: Maximizing LDL-C Lowering','type'=>'lecture'],
                    ['title'=>'Discussion','type'=>'discussion']
                ]
            ],

            8 => [
                'title'=>'International Session: Pulmonary Hypertension Across the Spectrum',
                'moderator'=>'Dr. dr. Mefri Yanni, Sp.JP (K)',
                'speakers'=>[
                    'dr. Ting Ting Low',
                    'dr. Geetha Kandavello'
                ],
                'sponsors'=>[
                    ['name'=>'Aurogen','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614001/PandanViewMandeh/ipm74ssodwzls0ptnr3n.png"],
                    ['name'=>'Fahrenheit','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614284/PandanViewMandeh/hntrub8op74cvhohrb2p.png"]
                ],
                'topics'=>[
                    ['title'=>'Pulmonary Hypertension Revisited','type'=>'lecture'],
                    ['title'=>'Managing Late Presentation Pulmonary Hypertension','type'=>'lecture'],
                    ['title'=>'Discussion','type'=>'discussion']
                ]
            ],

            9 => [
                'title'=>'International Session: Heart Failure – AI and Advanced Imaging',
                'moderator'=>'Dr. dr. Eka Fithra Elfi, Sp.JP (K)',
                'speakers'=>[
                    'Dr Diana Foo',
                    'Dr Ignasius Aditya Jappar'
                ],
                'sponsors'=>[
                    ['name'=>'Novartis','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772613692/PandanViewMandeh/oxq7sese72ajlh7fjfhj.png"],
                    ['name'=>'Merck','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614438/PandanViewMandeh/xxfbiqszyxg0cen1mcuh.png"]
                ],
                'topics'=>[
                    ['title'=>'Artificial Intelligence in Heart Failure','type'=>'lecture'],
                    ['title'=>'Advanced Cardiac Imaging for HF','type'=>'lecture'],
                    ['title'=>'Discussion','type'=>'discussion']
                ]
            ],

            10 => [
                'title'=>'Case in the Box: Pediatric and Structural Heart Disease (ASD)',
                'lecture'=>'dr Didik Hariyanto, Sp.A, (K)',
                'case_presenter'=>'dr. Harben Fernando, Sp.JP',
                'panelists'=>[
                    'dr. Didik Hariyanto, Sp.A, (K)',
                    'dr. Kino, Sp. JP (K)',
                    'dr. Mefri Yanni, Sp.JP (K)',
                    'dr. Hardiyansyah, Sp.BTKV'
                ],
                'sponsors'=>[
                    ['name'=>'Kalventis','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614488/PandanViewMandeh/zc5e1u15svhuwoydw0lt.png"],
                    ['name'=>'Dexa','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614546/PandanViewMandeh/t5qs8yhklcjn35dtzwuo.png"]
                ],
                'topics'=>[
                    ['title'=>'Atrial Septal Defect: From Pathophysiology to Decision','type'=>'lecture'],
                    ['title'=>'Evidence Based ASD Closure Case','type'=>'video']
                ]
            ],

            11 => [
                'title'=>'Integrating Cardiometabolic Care in the Era of GLP-1 RA and SGLT2 Inhibitors',
                'moderator'=>'dr. Rika Yandriani, Sp.JP',
                'speakers'=>[
                    'dr. Yerizal Karani, Sp.PD, Sp.JP (K)',
                    'dr. Masrul, Sp.PD, Sp.JP (K)'
                ],
                'sponsors'=>[
                    ['name'=>'Novo Nordisk','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614650/PandanViewMandeh/sf1kx48iu8ucqozzfoj1.jpg"],
                    ['name'=>'AstraZeneca','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620250/PandanViewMandeh/q6d3wr30vfwyb6mxj1qw.png"]
                ],
                'topics'=>[
                    ['title'=>'Role of GLP-1 Receptor Agonism','type'=>'lecture'],
                    ['title'=>'Cardiovascular Protection with SGLT2','type'=>'lecture'],
                    ['title'=>'Discussion','type'=>'discussion']
                ]
            ],

            12 => [
                'title'=>'Emergency in Cardiovascular: Crack the Clot Code – Pulmonary Embolism',
                'lecture'=>'dr. Eka Fithra Elfi, Sp.JP (K)',
                'case_presenter'=>'dr. Hamzah, Sp.JP',
                'panelists'=>[
                    'dr. Eka Fithra Elfi, Sp.JP (K)',
                    'dr. Yose Ramda Ilhami, Sp.JP (K)',
                    'dr. Wiza Erlanda, Sp.JP (K)',
                    'dr. Muhammad Riendra, Sp.BTKV'
                ],
                'sponsors'=>[
                    ['name'=>'Kalventis','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614488/PandanViewMandeh/zc5e1u15svhuwoydw0lt.png"],
                    ['name'=>'Dexa','logo_url'=>"https://res.cloudinary.com/dezj1x6xp/image/upload/v1772614546/PandanViewMandeh/t5qs8yhklcjn35dtzwuo.png"]
                ],
                'topics'=>[
                    ['title'=>'Pulmonary Embolism Recognition and Risk Stratification','type'=>'lecture'],
                    ['title'=>'High Stakes Pulmonary Embolism Case Illustration','type'=>'case']
                ]
            ]

        ];


        foreach ($symposia as $number => $data) {

            $activity = Activity::create([
                'event_id'=>$eventId,
                'category'=>'symposium',
                'code'=>'SYM-'.str_pad($number,2,'0',STR_PAD_LEFT),
                'title'=>$data['title'],
                'moderator'=>$data['moderator'] ?? null,
                'lecture'=>$data['lecture'] ?? null,
                'case_presenter'=>$data['case_presenter'] ?? null,
                'is_paid'=>true
            ]);


            foreach ($data['topics'] as $index=>$topic) {
                ActivityTopic::create([
                    'activity_id'=>$activity->id,
                    'title'=>$topic['title'],
                    'type'=>$topic['type'],
                    'order'=>$index+1
                ]);
            }


            if(isset($data['speakers'])){
                foreach($data['speakers'] as $speaker){
                    ActivitySpeaker::create([
                        'activity_id'=>$activity->id,
                        'name'=>$speaker
                    ]);
                }
            }


            if(isset($data['panelists'])){
                foreach($data['panelists'] as $panelist){
                    ActivityPanelist::create([
                        'activity_id'=>$activity->id,
                        'name'=>$panelist
                    ]);
                }
            }


            if(isset($data['sponsors'])){
                foreach($data['sponsors'] as $sponsor){
                    ActivitySponsor::create([
                        'activity_id'=>$activity->id,
                        'name'=>$sponsor['name'],
                        'logo_url'=>$sponsor['logo_url']
                    ]);
                }
            }

        }

        /**
         * =====================================================
         * WORKSHOP + TOPICS
         * =====================================================
         */
        $workshops = [

            'WS-01' => [
                'title' => 'Ultrasound in Cardiovascular Case (POCUS)',

                'speakers' => [
                    'dr. Mefri Yanni, Sp.JP (K)',
                    'dr. Nani, Sp.JP (K)',
                    'dr. Putri Handayani, Sp.JP'
                ],

                'moderator' => null,

                'pic' => 'dr. Putri Handayani, Sp.JP',

                'sponsors' => [
                    [
                        'name' => 'GE Healthcare',
                        'logo_url' => "https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620325/PandanViewMandeh/gxfegbhhyjnokpogrqls.png"
                    ],
                    [
                        'name' => 'Philips',
                        'logo_url' => "https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620409/PandanViewMandeh/fwz2aqnvdzjck1y7ij5i.png"
                    ]
                ],

                'topics' => [
                    ['title' => 'Basic Cardiac Ultrasound and Image Acquisition', 'type' => 'lecture'],
                    ['title' => 'LV-RV Function and Common Pathology in POCUS Echo', 'type' => 'lecture'],
                    ['title' => 'From Image to Clinical Decision in POCUS Echo', 'type' => 'lecture'],
                    ['title' => 'Hands On Session', 'type' => 'case'],
                ],
            ],

            'WS-02' => [
                'title' => 'Basic Mechanical Ventilation in Cardiac Emergency',

                'speakers' => [
                    'Dr. dr. Yose Ramda Ilhami, Sp.JP (K)',
                    'dr. Wiza Erlanda, Sp.JP (K)',
                    'dr. Vera Yulia, Sp.JP (K)'
                ],

                'pic' => 'dr. Wiza Erlanda, Sp.JP (K)',

                'sponsors' => [],

                'topics' => [
                    ['title' => 'Basic Mechanical Ventilation', 'type' => 'lecture'],
                    ['title' => 'Initial Setting and Monitoring in Mechanical Ventilation', 'type' => 'lecture'],
                    ['title' => 'Sedative, Analgetic, and Paralytic Agents', 'type' => 'lecture'],
                    ['title' => 'Methods of Weaning Procedure', 'type' => 'lecture'],
                ],
            ],

            'WS-03' => [
                'title' => 'Cardiac Rehabilitation in Primary and Secondary Health Setting',

                'speakers' => [
                    'dr. Rita Hamdani, Sp.JP (K)',
                    'dr. Citra Kiki Krevani, Sp.JP (K)'
                ],

                'pic' => 'dr. Rita Hamdani, Sp.JP (K)',

                'sponsors' => [
                    [
                        'name' => 'BTL',
                        'logo_url' => "https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620469/PandanViewMandeh/wdhcgxxgs1nce66hox46.png"
                    ],
                    [
                        'name' => 'Mindray',
                        'logo_url' => "https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620502/PandanViewMandeh/x3e42ews313cflkdtgnj.jpg"
                    ]
                ],

                'topics' => [
                    ['title' => 'Referral and Integration into Primary Care', 'type' => 'lecture'],
                    ['title' => 'Functional Capacity Assessment Using Simple Clinical Tools', 'type' => 'lecture'],
                    ['title' => 'Exercise Prescription in Cardiac Rehabilitation Program', 'type' => 'lecture'],
                ],
            ],

            'WS-04' => [
                'title' => 'Arrhythmia in Heart Failure',
                'moderator'=>'dr. Benny Antama Syant, Sp.JP',
                'speakers' => [
                    'dr. Hauda El Rasyid, Sp.JP (K)',
                    'Dr. dr. Tommy Daindes, Sp.JP (K)',
                    'dr. Wahyudi, Sp.PD, K-KV',
                    'dr. Taufik Rizkian Asir, Sp.PD, K-KV',
                    'dr. Benny Antama Syant, Sp.JP',
                ],

                'pic' => 'Dr. dr. Tommy Daindes, Sp.JP (K)',

                'sponsors' => [
                    [
                        'name' => 'Abbott',
                        'logo_url' => "https://res.cloudinary.com/dezj1x6xp/image/upload/v1772620543/PandanViewMandeh/mvlkzfetwbyapnswvcbp.svg"
                    ]
                ],

                'topics' => [
                    ['title' => 'Ischemic Cardiomyopathy', 'type' => 'lecture'],
                    ['title' => 'Non-Ischemic Cardiomyopathy', 'type' => 'lecture'],
                    ['title' => 'Atrial Arrhythmia in Heart Failure', 'type' => 'lecture'],
                    ['title' => 'Ventricular Arrhythmia and CIED in Heart Failure', 'type' => 'lecture'],
                ],
            ],

        ];

        foreach ($workshops as $code => $data) {

            $activity = Activity::create([
                'event_id' => $eventId,
                'category' => 'workshop',
                'code' => $code,
                'title' => $data['title'],
                'pic' => $data['pic'] ?? null,
                'moderator' => $data['moderator'] ?? null,
                'quota' => 1,
                'is_paid' => true,
            ]);


            // Topics
            if (!empty($data['topics'])) {

                foreach ($data['topics'] as $index => $topic) {

                    ActivityTopic::create([
                        'activity_id' => $activity->id,
                        'title' => $topic['title'],
                        'type' => $topic['type'],
                        'order' => $index + 1,
                    ]);

                }
            }


            // Speakers
            if (!empty($data['speakers'])) {

                foreach ($data['speakers'] as $speaker) {

                    ActivitySpeaker::create([
                        'activity_id' => $activity->id,
                        'name' => $speaker,
                    ]);

                }
            }


            // Sponsors
            if (!empty($data['sponsors'])) {

                foreach ($data['sponsors'] as $sponsor) {

                    ActivitySponsor::create([
                        'activity_id' => $activity->id,
                        'name' => $sponsor['name'],
                        'logo_url' => $sponsor['logo_url'] ?? null,
                    ]);

                }
            }

        }

        /**
         * =====================================================
         * CARDIOLOGY IN JEOPARDY
         * =====================================================
         */
        Activity::create([
            'event_id' => $eventId,
            'category' => 'jeopardy',
            'code' => 'JEOPARDY',
            'title' => 'Final Cardiology in Jeopardy',
            'is_paid' => false,
        ]);
    }
}
