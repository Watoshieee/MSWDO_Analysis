<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $announcements = [
            [
                'title' => '🎉 Welcome to MSWDO Online Application System!',
                'content' => 'We are pleased to announce that our online application system is now live! You can now apply for various social welfare programs online. Please ensure you have all the required documents ready before submitting your application. For inquiries, please visit our office or contact us at (049) 123-4567.',
                'type' => 'success',
                'is_active' => true
            ],
            [
                'title' => '📢 Reminder: 4Ps Program Requirements',
                'content' => 'For 4Ps applicants, please make sure to submit the following complete requirements: Birth Certificates of all family members, School IDs or Report Cards of children, Barangay Certificate, Valid ID, 1x1 Pictures, and Health Records for children 0-5 years old. Incomplete requirements may cause delay in processing.',
                'type' => 'warning',
                'is_active' => true
            ],
            [
                'title' => '👴 Senior Citizen ID Application',
                'content' => 'Senior citizens applying for Senior Citizen ID must submit: OSCA Application Form, ID Photos (2 pcs 1x1), Birth Certificate or Valid ID, Barangay Certificate (if needed), Voter\'s Certification (if needed), and Authorization Letter if applicable. Processing takes 3-5 working days.',
                'type' => 'info',
                'is_active' => true
            ],
            [
                'title' => '♿ PWD ID Application',
                'content' => 'For PWD ID applicants: New applicants need Voter\'s ID, Medical Certificate, Registration Form with Cedula, Barangay & President Certification, and Birth Certificate. For renewal, please submit Voter\'s ID, Medical Certificate, Affidavit (Sinumpaang Salaysay), Birth Certificate, and Payment of PHP 100.',
                'type' => 'info',
                'is_active' => true
            ],
            [
                'title' => '👨‍👩‍👧 Solo Parent ID Requirements',
                'content' => 'Solo Parent applicants must submit: Application Form, Cedula, Voter\'s ID, Birth Certificate (for minor children), and Barangay Certification. Please ensure all documents are original and clear copies. Processing time is 5-7 working days.',
                'type' => 'info',
                'is_active' => true
            ],
            [
                'title' => '🏆 Congratulations to Our Approved Applicants!',
                'content' => 'We would like to congratulate all applicants whose requirements have been approved! You may now proceed to the MSWDO Office to claim your benefits. Please bring the printed copies of your submitted requirements. Office hours are Monday to Friday, 8:00 AM to 5:00 PM.',
                'type' => 'success',
                'is_active' => true
            ],
        ];
        
        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}