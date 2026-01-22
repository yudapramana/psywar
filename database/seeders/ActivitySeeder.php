<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Activity;
use App\Models\ActivityTopic;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $eventId = Event::first()->id;

        /**
         * =====================================================
         * SYMPOSIUM + TOPICS (SESUI PROPOSAL)
         * =====================================================
         */
        $symposia = [
            1 => [
                'title' => 'The Evolving Landscape of ACS Care: Evidence and Experience',
                'topics' => [
                    ['title' => 'Pain Relief Through Ischemia Control in Acute Coronary Syndrome: The Expanding Role of Anti-Ischemic Therapy', 'type' => 'lecture'],
                    ['title' => 'Walking the Tightrope: Balancing Antithrombotic Potency and Bleeding Risks in ACS', 'type' => 'lecture'],
                    ['title' => 'Discussion', 'type' => 'discussion'],
                ],
            ],
            2 => [
                'title' => 'International Session: Heart Failure Across the Ejection Fraction Continuum',
                'topics' => [
                    ['title' => 'Heart Failure Across the Ejection Fraction Continuum: Translating Evidence Into Individualized Care', 'type' => 'lecture'],
                    ['title' => 'Beyond Recovery: Clinical Meaning and Long-Term Management of HF with Improved EF', 'type' => 'lecture'],
                    ['title' => 'Discussion', 'type' => 'discussion'],
                ],
            ],
            3 => [
                'title' => 'Case in the Box: Managing High Calcified Lesion in Acute Coronary Syndrome',
                'topics' => [
                    ['title' => 'Before the Stent Hits the Landing Station: Getting ACS Right from the Start', 'type' => 'lecture'],
                    ['title' => 'When Plaque Turns to Stone: Role of Intracoronary Imaging and Atherectomy', 'type' => 'video'],
                ],
            ],
            4 => [
                'title' => 'Redefining Heart Failure Management: Beyond Ejection Fraction',
                'topics' => [
                    ['title' => 'Beyond Ejection Fraction: Redefining HF as a Progressive Disease', 'type' => 'lecture'],
                    ['title' => 'When Normal EF Is Not Enough: A Case Illustration of Progressive HF', 'type' => 'case'],
                ],
            ],
            5 => [
                'title' => 'Daily Practice and First-Line Antihypertensive Therapy: What, When, and Why?',
                'topics' => [
                    ['title' => 'Modern Antihypertensive Therapy: Choosing the Right Drug for the Right Patient', 'type' => 'lecture'],
                    ['title' => 'Combination Therapy as First-Line Treatment: Evidence, Strategy, and Pitfalls', 'type' => 'lecture'],
                    ['title' => 'Discussion', 'type' => 'discussion'],
                ],
            ],
            6 => [
                'title' => 'Debate Session: Puncture or Slice – Optimal Myocardial Revascularization',
                'topics' => [
                    ['title' => 'Case Presentation: Puncture or Slice Strategy', 'type' => 'case'],
                    ['title' => 'Debate Session', 'type' => 'discussion'],
                ],
            ],
            7 => [
                'title' => 'Prevention: Residual Cardiovascular Risk After LDL Control',
                'topics' => [
                    ['title' => 'Beyond Cholesterol Numbers: Lipid Lowering and Cardiovascular Outcomes', 'type' => 'lecture'],
                    ['title' => 'Two Pathways, One Goal: Maximizing LDL-C Lowering', 'type' => 'lecture'],
                    ['title' => 'Discussion', 'type' => 'discussion'],
                ],
            ],
            8 => [
                'title' => 'International Session: Pulmonary Hypertension Across the Spectrum',
                'topics' => [
                    ['title' => 'Pulmonary Hypertension Revisited: Hemodynamics and Molecular Pathways', 'type' => 'lecture'],
                    ['title' => 'Advancing PH Care Through Targeted Vasodilator Therapy', 'type' => 'lecture'],
                    ['title' => 'Discussion', 'type' => 'discussion'],
                ],
            ],
            9 => [
                'title' => 'Case in the Box: Decoding Supraventricular Tachycardia from ECG to Ablation',
                'topics' => [
                    ['title' => 'Supraventricular Tachycardia 101', 'type' => 'lecture'],
                    ['title' => 'Step-by-Step Video: From ECG to Curative Ablation', 'type' => 'video'],
                ],
            ],
            10 => [
                'title' => 'Emergency in Cardiovascular: Crack the Clot Code – Pulmonary Embolism',
                'topics' => [
                    ['title' => 'Pulmonary Embolism Made Practical: Recognition and Risk Stratification', 'type' => 'lecture'],
                    ['title' => 'Case Illustration: High-Stakes Pulmonary Embolism', 'type' => 'case'],
                ],
            ],
            11 => [
                'title' => 'Integrating Cardiometabolic Care in the Era of GLP-1 RA and SGLT2 Inhibitors',
                'topics' => [
                    ['title' => 'Role of GLP-1 Receptor Agonism in Cardiometabolic Risk', 'type' => 'lecture'],
                    ['title' => 'From Metabolic Modulation to Cardiovascular Protection with SGLT2 Inhibitors', 'type' => 'lecture'],
                    ['title' => 'Discussion', 'type' => 'discussion'],
                ],
            ],
            12 => [
                'title' => 'Case in the Box: Pediatric and Structural Heart Disease (ASD)',
                'topics' => [
                    ['title' => 'Atrial Septal Defect: From Pathophysiology to Decision-Making', 'type' => 'lecture'],
                    ['title' => 'Video Case: Evidence-Based ASD Closure', 'type' => 'video'],
                ],
            ],
        ];

        foreach ($symposia as $number => $data) {
            $activity = Activity::create([
                'event_id' => $eventId,
                'category' => 'symposium',
                'code' => 'SYM-' . str_pad($number, 2, '0', STR_PAD_LEFT),
                'title' => $data['title'],
                'is_paid' => true,
            ]);

            foreach ($data['topics'] as $index => $topic) {
                ActivityTopic::create([
                    'activity_id' => $activity->id,
                    'title' => $topic['title'],
                    'type' => $topic['type'],
                    'order' => $index + 1,
                ]);
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
                'topics' => [
                    ['title' => 'Basic Cardiac Ultrasound and Image Acquisition', 'type' => 'lecture'],
                    ['title' => 'LV-RV Function and Common Pathology in POCUS Echo', 'type' => 'lecture'],
                    ['title' => 'From Image to Clinical Decision in POCUS Echo', 'type' => 'lecture'],
                    ['title' => 'Hands On Session', 'type' => 'case'],
                ],
            ],
            'WS-02' => [
                'title' => 'Basic Mechanical Ventilation in Cardiac Emergency',
                'topics' => [
                    ['title' => 'Basic Mechanical Ventilation', 'type' => 'lecture'],
                    ['title' => 'Initial Setting and Monitoring in Mechanical Ventilation', 'type' => 'lecture'],
                    ['title' => 'Sedative, Analgetic, and Paralytic Agents', 'type' => 'lecture'],
                    ['title' => 'Methods of Weaning Procedure', 'type' => 'lecture'],
                ],
            ],
            'WS-03' => [
                'title' => 'Cardiac Rehabilitation in Primary and Secondary Health Setting',
                'topics' => [
                    ['title' => 'Referral and Integration into Primary Care', 'type' => 'lecture'],
                    ['title' => 'Functional Capacity Assessment Using Simple Clinical Tools', 'type' => 'lecture'],
                    ['title' => 'Exercise Prescription in Cardiac Rehabilitation Program', 'type' => 'lecture'],
                ],
            ],
            'WS-04' => [
                'title' => 'Arrhythmia in Heart Failure',
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
                'quota' => 50,
                'is_paid' => true,
            ]);

            foreach ($data['topics'] as $index => $topic) {
                ActivityTopic::create([
                    'activity_id' => $activity->id,
                    'title' => $topic['title'],
                    'type' => $topic['type'],
                    'order' => $index + 1,
                ]);
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
