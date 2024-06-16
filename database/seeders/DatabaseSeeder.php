<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Developmental data seeding
        //
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        //TODO: Transition to using model factories for seeding

        //User table seeding
        $users = [
            [
                'id' => '1',
                'name' => 'Leon Jensen Tan',
                'email' => 'leonjensentan@gmail.com',
                'email_verified_at' => '2024-01-26 07:55:21',
                'password' => '$2y$10$oNXY54l.PRY35cTAlYRjd.WJFGZZT6isgV7ejBK.c7HVKg4yoSpXq',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 07:55:21',
                'updated_at' => '2024-02-21 05:42:20',
            ],
            [
                'id' => '2',
                'name' => 'Lee Jong Suk',
                'email' => 'ljs@gmail.com',
                'email_verified_at' => '2024-01-26 08:42:13',
                'password' => '$2y$10$43/hzujXxtXg8S8LS1.8TeQOikJ3J43IWLBSt4W/rBuWFewRGi5G2',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 08:42:13',
                'updated_at' => '2024-02-21 05:42:04',
            ],
            [
                'id' => '3',
                'name' => 'Bae Suzy',
                'email' => 'baesuzy@gmail.com',
                'email_verified_at' => '2024-01-26 08:44:17',
                'password' => '$2y$10$oB9qz5kQfbY9RJTjimWaUu5gff3npCNRwQuMspbPrAsDV7motfX8y',
                'remember_token' => NULL,
                'created_at' => '2024-01-26 08:44:17',
                'updated_at' => '2024-01-26 08:44:17',
            ],
            [
                'id' => '4',
                'name' => 'Lee Dong Wook',
                'email' => 'leedongwook@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$LDRhctkvOkCBXnBZCO3skuDyDQTlz32lSORSTpqddTnGtSzBGKxbG',
                'remember_token' => NULL,
                'created_at' => '2024-01-28 07:21:47',
                'updated_at' => '2024-01-28 07:21:47',
            ],
            [
                'id' => '5',
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$Jiz.Nl6ypw7q4cm.9mNzuOvyQ4Wzzouo/jRkajccgWLCPcG/mwFwy',
                'remember_token' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ]
        ];

        DB::table('users')->insert($users);

        //Company table seeding
        $companies = [
            [
                'CompanyID' => '1',
                'Name' => 'Atomic Rush',
                'Industry' => 'IT',
                'Address' => 'Silicon Valley, California, USA',
                'Website' => 'https://www.google.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
                'button_color' => '#A6708E',
                'sidebar_color' => '#6c2147',
                'company_logo' => 'CompanyLogos/pp-logo-white.jpg',
                'subscription_starts_at' => '2024-01-20 08:00:00',
                'subscription_ends_at' => '2024-06-28 08:00:00',
            ],
            [
                'CompanyID' => '2',
                'Name' => 'YG Entertainment',
                'Industry' => 'Media and Entertainment',
                'Address' => 'Seoul, South Korea',
                'Website' => 'https://www.facebook.com',
                'created_at' => '2024-01-26 08:42:00',
                'updated_at' => '2024-01-26 08:42:00',
                'button_color' => '#000',
                'sidebar_color' => '#fff',
                'company_logo' => 'CompanyLogos/yg-logo.jpg',
                'subscription_starts_at' => '2024-01-20 08:00:00',
                'subscription_ends_at' => '2025-01-20 08:00:00',
            ],
            [
                'CompanyID' => '3',
                'Name' => 'Apple',
                'Industry' => 'Technology',
                'Address' => '1 Apple Park Way, Cupertino, CA 95014, USA',
                'Website' => 'https://www.apple.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
                'button_color' => '##c6c6c6',
                'sidebar_color' => '#fff',
                'company_logo' => 'CompanyLogos/apple-logo.jpg',
                'subscription_starts_at' => '2024-02-10 08:00:00',
                'subscription_ends_at' => '2026-01-11 08:00:00',
            ],
            [
                'CompanyID' => '4',
                'Name' => 'Microsoft',
                'Industry' => 'Technology',
                'Address' => 'One Microsoft Way, Redmond, WA 98052, USA',
                'Website' => 'https://www.microsoft.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
                'button_color' => '#00a1f1',
                'sidebar_color' => '#ffbb00',
                'company_logo' => 'CompanyLogos/ms-logo.jpg',
                'subscription_starts_at' => '2024-03-16 08:00:00',
                'subscription_ends_at' => '2025-03-16 08:00:00',
            ],
            [
                'CompanyID' => '5',
                'Name' => 'Amazon',
                'Industry' => 'Technology',
                'Address' => '410 Terry Ave N, Seattle, WA 98109, USA',
                'Website' => 'https://www.amazon.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
                'button_color' => '#ff9900',
                'sidebar_color' => '#000',
                'company_logo' => 'CompanyLogos/amazon-logo.jpg',
                'subscription_starts_at' => '2024-01-25 08:00:00',
                'subscription_ends_at' => '2026-01-25 08:00:00',
            ]
        ];

        DB::table('companies')->insert($companies);

        //Profile table seeding
        $profiles = [
            [
                'profile_id' => '1',
                'user_id' => '1',
                'employee_id' => 'AR00001',
                'name' => 'Leon Jensen Tan',
                'gender'=> 'Male',
                'dob' => '2001-09-06',
                'age' => '23',
                'position' => 'Technical Lead',
                'dept' => 'Development',
                'bio' => 'I love Valorant23',
                'phone_no' => '0183939376',
                'address' => 'Sri Damansara',
                'profile_picture' => 'profile_pictures/leon.png',
                'created_at' => '2024-01-26 08:39:33',
                'updated_at' => '2024-02-21 05:42:20',
            ],
            [
                'profile_id' => '2',
                'user_id' => '2',
                'employee_id' => 'AR00002',
                'name' => 'Lee Jong Suk',
                'gender'=> 'Male',
                'dob' => '2001-01-01',
                'age' => '23',
                'position' => 'Actor',
                'dept' => 'Talents',
                'bio' => 'I love acting2',
                'phone_no' => '0123456789',
                'address' => 'Seoul',
                'profile_picture' => 'profile_pictures/leejongsuk.jpg',
                'created_at' => '2024-01-26 08:45:15',
                'updated_at' => '2024-01-28 08:41:20',
            ],
            [
                'profile_id' => '3',
                'user_id' => '3',
                'employee_id' => 'YG00001',
                'name' => 'Bae Suzy',
                'gender'=> 'Female',
                'dob' => '2001-01-01',
                'age' => '23',
                'position' => 'Actress',
                'dept' => 'Talents',
                'bio' => 'I love acting',
                'phone_no' => '0123456789',
                'address' => 'Seoul',
                'profile_picture' => 'profile_pictures/baesuzy.jpg',
                'created_at' => '2024-01-26 08:46:27',
                'updated_at' => '2024-01-26 08:46:27',
            ],
            [
                'profile_id' => '4',
                'user_id' => '4',
                'employee_id' => 'AR00003',
                'name' => 'Lee Dong Wook',
                'gender'=> 'Male',
                'dob' => '2024-01-04',
                'age' => '23',
                'position' => 'Actor',
                'dept' => 'Talents',
                'bio' => 'Acting',
                'phone_no' => '0123456789',
                'address' => 'Seoul',
                'profile_picture' => 'profile_pictures/leedongwook.jpg',
                'created_at' => '2024-01-28 07:21:47',
                'updated_at' => '2024-01-28 07:21:47',
            ],
            [
                'profile_id' => '5',
                'user_id' => '5',
                'employee_id' => 'SA00001',
                'name' => 'Super Admin',
                'gender'=> 'Male',
                'dob' => '2024-02-01',
                'age' => '24',
                'position' => 'Super Admin',
                'dept' => 'Super Admin',
                'bio' => 'Super Admin',
                'phone_no' => '0123456789',
                'address' => 'Super Admin',
                'profile_picture' => 'profile_pictures/leon.png',
                'created_at' => NULL,
                'updated_at' => NULL,
            ]
        ];

        DB::table('profiles')->insert($profiles);

        //CompanyUser table seeding
        $companyusers = [
            [
                //'CUID' => CompanyUser::generateUlid(),
                'UserID' => '1',
                'CompanyID' => '1',
                'created_at' => '2024-01-26 08:26:22',
                'updated_at' => '2024-02-21 05:42:20',
                'isAdmin' => '1',
            ],
            [
                //'CUID' => CompanyUser::generateUlid(),
                'UserID' => '2',
                'CompanyID' => '1',
                'created_at' => '2024-01-26 08:44:48',
                'updated_at' => '2024-02-21 05:42:04',
                'isAdmin' => '0',
            ],
            [
                //'CUID' => CompanyUser::generateUlid(),
                'UserID' => '3',
                'CompanyID' => '2',
                'created_at' => '2024-01-26 08:44:59',
                'updated_at' => '2024-01-26 08:44:59',
                'isAdmin' => '0',
            ],
            [
                //'CUID' => CompanyUser::generateUlid(),
                'UserID' => '4',
                'CompanyID' => '1',
                'created_at' => '2024-01-28 07:21:47',
                'updated_at' => '2024-01-28 07:21:47',
                'isAdmin' => '0',
            ]
        ];

        DB::table('companyusers')->insert($companyusers);

        //SuperAdmin table seeding
        $superadmins = [
            [
                'AdminID' => '1',
                'UserID' => '5',
                'created_at' => '2024-02-21 05:42:20',
                'updated_at' => '2024-02-21 05:42:20',
            ]
        ];

        DB::table('superadmins')->insert($superadmins);

        //Post table seeding
        $posts = [
            [
                'PostID' => '1',
                'UserID' => '1',
                'CompanyID' => '1',
                'title' => 'How to access the discussion page?',
                'content' => 'Hi, I am unable to access the discussion page. I do not know how to access it. Can someone help.',
                'is_answered' => '1',
                'is_locked' => '0',
                'is_archived' => '0',
                'is_anonymous' => '0',
                'created_at' => '2024-02-28 11:09:59',
                'updated_at' => NULL,
            ],
            [
                'PostID' => '2',
                'UserID' => '2',
                'CompanyID' => '1',
                'title' => 'How to access Machine Learning Chatbot?',
                'content' => 'I am unable to access the machine learning chatbot, pls help',
                'is_answered' => '1',
                'is_locked' => '0',
                'is_archived' => '0',
                'is_anonymous' => '0',
                'created_at' => '2024-03-02 08:31:31',
                'updated_at' => NULL,
            ],
            [
                'PostID' => '3',
                'UserID' => '1',
                'CompanyID' => '1',
                'title' => 'How to access the onboarding modules',
                'content' => 'I cant access the onboarding modules, please help',
                'is_answered' => '0',
                'is_locked' => '0',
                'is_archived' => '0',
                'is_anonymous' => '0',
                'created_at' => '2024-03-02 08:31:31',
                'updated_at' => NULL,
            ],
            [
                'PostID' => '4',
                'UserID' => '1',
                'CompanyID' => '1',
                'title' => 'How to access the employee page?',
                'content' => 'I am unable to access the employee page, pls help',
                'is_answered' => '0',
                'is_locked' => '0',
                'is_archived' => '0',
                'is_anonymous' => '0',
                'created_at' => '2024-03-02 08:31:31',
                'updated_at' => NULL,
            ]
        ];

        DB::table('post')->insert($posts);

        //Answer table seeding
        $answers = [
            [
                'UserID' => '2',
                'CompanyID' => '1',
                'PostID' => '1',
                'content' => 'The discussion page is accessible on the navigation sidebar that is residing on the left of the screen. It should be under the "Module" selection on the navigation side bar',
                'created_at' => '2024-02-28 11:09:59',
                'updated_at' => NULL,
                'is_anonymous' => '0',
            ]
        ];

        DB::table('answer')->insert($answers);

        //Module table seeding
        $modules = [
            [
                'id' => 1,
                'title' => 'My First Module',
                'image_path' => 'modules/1718076298.jpg',
                'CompanyID' => 1,
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'title' => 'My Second Module',
                'image_path' => 'modules/1718076452.jpg',
                'CompanyID' => 1,
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
            ]
        ];

        DB::table('modules')->insert($modules);

        //Chapter table seeding
        $chapters = [
            [
                'id' => 1,
                'module_id' => 1,
                'title' => 'Chapter 1 : Welcome to People Psyence',
                'description' => 'In this introductory chapter, we warmly welcome you to People Psyence. 
                This chapter aims to provide you with a comprehensive overview of our organization, our mission, and the values that guide us. 
                You will get to know the team, understand the structure of our organization, and learn about your role and the resources available to support your success.
                This foundational chapter sets the stage for your exciting journey with us, ensuring you feel welcomed and well-prepared as you begin your new role.',
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'module_id' => 1,
                'title' => 'Chapter 2: Orientation and Training',
                'description' => 'Chapter 2 focuses on equipping you with the knowledge and skills necessary to thrive in your new role at People Psyence. 
                This chapter includes a detailed orientation to our organizations systems, processes, and culture. 
                You will participate in training sessions designed to provide you with essential tools and information, 
                ensuring you are well-prepared to contribute effectively to our team. By the end of this chapter, you will have a solid understanding of our operational procedures, 
                key policies, and your specific responsibilities, setting you up for a successful start.',
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
            ]
        ];

        DB::table('chapters')->insert($chapters);

         //Page/Item table seeding
         $items = [
            [
                'id' => 1,
                'chapter_id' => 1,
                'title' => 'Page 1: A Warm Welcome to People Psyence',
                'description' => '<p>Welcome to the People Psyence family! This page provides a warm introduction to our organization and outlines what you can expect from this welcome module. 
                You will learn about our team, mission, values, organizational structure, your specific role, and the resources available to support your success. 
                This is your first step toward becoming an integral part of our mission to advance psychological science and improve mental health outcomes. Welcome aboard!</p>',
                'content' => '<p><img src="../../storage/TinyMceImages/1718076943.jpg" alt="" width="629" height="271"></p>
                            <p>Welcome to the People Psyence family! We are excited to have you on board and look forward to the unique contributions you will bring to our team. This chapter is designed to provide you with a warm introduction to our organization and set the stage for your journey with us.</p>
                            <h4>What to Expect from This Module</h4>
                            <p>In this welcome module, you will:</p>
                            <ol>
                            <li><strong>Meet the Team</strong>: Get to know the diverse and talented individuals who make up People Psyence. You will learn about our leadership, key team members, and their roles within the organization.</li>
                            <li><strong>Our Mission and Values</strong>: Gain a deeper understanding of our mission, vision, and core values that drive everything we do.</li>
                            <li><strong>Organizational Overview</strong>: Explore the structure of People Psyence, including our various departments, research initiatives, and community outreach programs.</li>
                            <li><strong>Your Role</strong>: Learn about your specific role and how it fits into the larger picture of our work. This includes an overview of your responsibilities, expectations, and goals.</li>
                            <li><strong>Resources and Support</strong>: Discover the resources available to you, from training materials and professional development opportunities to support systems designed to help you succeed.</li>
                            </ol>
                            <p>We believe that a smooth and informative onboarding process is crucial for your success and satisfaction in your new role. This module is your first step towards becoming an integral part of our mission to advance psychological science and improve mental health outcomes.</p>
                            <p>Welcome aboard, and let us embark on this exciting journey together!</p>',
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
                'pdf_attachments' => '[]',
                'order' => 1,
            ],
            [
                'id' => 2,
                'chapter_id' => 1,
                'title' => 'Page 2: Understanding Our Culture and Policies',
                'description' => '<p>Welcome to your second page in the Welcome chapter. At People Psyence, we believe that our culture and policies are the foundation of our success and the well-being of our team members. This page will give you an overview of our organizational culture and direct you to our comprehensive policies document.</p>',
                'content' => '<h4>Our Culture</h4>
                                <p>At People Psyence, we foster a culture of innovation, collaboration, and excellence. Here&rsquo;s what you can expect from our work environment:</p>
                                <ul>
                                <li><strong>Innovation</strong>: We encourage creative thinking and continuous improvement. Your ideas and initiatives are highly valued and welcomed.</li>
                                <li><strong>Collaboration</strong>: Teamwork is at the heart of what we do. We believe in the power of diverse perspectives and working together to achieve common goals.</li>
                                <li><strong>Excellence</strong>: We strive for the highest standards in our research, practices, and interactions. Every team member is committed to delivering their best.</li>
                                <li><strong>Integrity</strong>: Ethical behavior and honesty are paramount. We uphold integrity in all our actions and decisions.</li>
                                <li><strong>Support</strong>: We provide a supportive and inclusive environment where everyone can thrive. Your growth and well-being are important to us.</li>
                                </ul>
                                <h4>Company Policies</h4>
                                <p>Our policies are designed to ensure a safe, productive, and respectful workplace for everyone. These policies cover a wide range of areas including:</p>
                                <ul>
                                <li><strong>Code of Conduct</strong>: Guidelines on professional behavior and interactions within the workplace.</li>
                                <li><strong>Equal Opportunity</strong>: Commitment to diversity, equity, and inclusion in all our practices.</li>
                                <li><strong>Health and Safety</strong>: Procedures and protocols to maintain a safe working environment.</li>
                                <li><strong>Privacy and Confidentiality</strong>: Policies on handling sensitive information and respecting privacy.</li>
                                <li><strong>Work Hours and Leave</strong>: Information on working hours, leave entitlements, and procedures.</li>
                                </ul>
                                <p>For a detailed understanding of our policies, please refer to the</p>
                                <h4>Our Culture</h4>
                                <p>At People Psyence, we foster a culture of innovation, collaboration, and excellence. Here&rsquo;s what you can expect from our work environment:</p>
                                <ul>
                                <li><strong>Innovation</strong>: We encourage creative thinking and continuous improvement. Your ideas and initiatives are highly valued and welcomed.</li>
                                <li><strong>Collaboration</strong>: Teamwork is at the heart of what we do. We believe in the power of diverse perspectives and working together to achieve common goals.</li>
                                <li><strong>Excellence</strong>: We strive for the highest standards in our research, practices, and interactions. Every team member is committed to delivering their best.</li>
                                <li><strong>Integrity</strong>: Ethical behavior and honesty are paramount. We uphold integrity in all our actions and decisions.</li>
                                <li><strong>Support</strong>: We provide a supportive and inclusive environment where everyone can thrive. Your growth and well-being are important to us.</li>
                                </ul>
                                <h4>Company Policies</h4>
                                <p>Our policies are designed to ensure a safe, productive, and respectful workplace for everyone. These policies cover a wide range of areas including:</p>
                                <ul>
                                <li><strong>Code of Conduct</strong>: Guidelines on professional behavior and interactions within the workplace.</li>
                                <li><strong>Equal Opportunity</strong>: Commitment to diversity, equity, and inclusion in all our practices.</li>
                                <li><strong>Health and Safety</strong>: Procedures and protocols to maintain a safe working environment.</li>
                                <li><strong>Privacy and Confidentiality</strong>: Policies on handling sensitive information and respecting privacy.</li>
                                <li><strong>Work Hours and Leave</strong>: Information on working hours, leave entitlements, and procedures.</li>
                                </ul>
                                <p>For a detailed understanding of our policies, please refer to the People Psyence Policies PDF attached below. This document is a comprehensive resource that outlines all our policies and procedures in detail. It is important to familiarize yourself with these policies to ensure compliance and to know your rights and responsibilities as a member of our team.</p>
                                <p>This document is a comprehensive resource that outlines all our policies and procedures in detail. It is important to familiarize yourself with these policies to ensure compliance and to know your rights and responsibilities as a member of our team.</p>',
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
                'pdf_attachments' => '[{"url":"\/storage\/pdf_attachments\/ih7pxrY3KI6G0oscNbWLzcNT11h2njxqslIr4HxG.pdf","name":"People Psyence Policies.pdf"}]',
                'order' => 2,
            ],
            [
                'id' => 3,
                'chapter_id' => 1,
                'title' => 'Quiz',
                'description' => null,
                'content' => null,
                'created_at' => '2024-06-16 05:05:45',
                'updated_at' => '2024-06-16 05:05:45',
                'deleted_at' => null,
                'pdf_attachments' => null,
                'order' => 3,
            ],
        ];

        DB::table('item')->insert($items);

        //Assign Module table seeding
        $assignmodule = [
            [
                'UserID' => 2,
                'CompanyID' => 1,
                'ModuleID' => 1,
                'DateAssigned' => '2024-06-11 23:18:05',
                'due_date' => '2024-06-30',
                'created_at' => '2024-06-11 11:24:59',
                'updated_at' => '2024-06-11 11:24:59',
                'deleted_at' => null,
            ],
        ];

        DB::table('assigned_module')->insert($assignmodule);

        //quizzes table seeding
        $quizzes = [
            [
                'id' => 1,
                'item_id' => 3,
                'title' => 'Quiz',
                'passing_score' => 3,
                'created_at' => '2024-06-16 05:42:20',
                'updated_at' => '2024-06-16 05:42:20',
            ]
        ];

        DB::table('quizzes')->insert($quizzes);

        //quizzes table seeding
        $quiz_questions = [
            [
                'id' => 1,
                'quiz_id' => 1,
                'question' => 'Hiring Manager',
                'type' => 'multiple_choice',
                'answer_options' => '"[\"Kamala\",\"Ben\"]"',
                'correct_answers' => '"1"',
                'created_at' => '2024-06-16 05:42:20',
                'updated_at' => '2024-06-16 05:42:20',
            ],
            [
                'id' => 2,
                'quiz_id' => 1,
                'question' => 'Company Name',
                'type' => 'short_answer',
                'answer_options' => '"[]"',
                'correct_answers' => '"People Psyence"',
                'created_at' => '2024-06-16 05:42:20',
                'updated_at' => '2024-06-16 05:42:20',
            ],
            [
                'id' => 3,
                'quiz_id' => 1,
                'question' => 'Ethics',
                'type' => 'checkbox',
                'answer_options' => '"[\"Turn off PC when leaving\",\"Leave used cup on desk\",\"Tap employee card when entering or leaving\"]"',
                'correct_answers' => '["0","2"]',
                'created_at' => '2024-06-16 05:42:20',
                'updated_at' => '2024-06-16 05:42:20',
            ],
        ];

        DB::table('quiz_questions')->insert($quiz_questions);

        //Call other seeder classes
        $this->call([
            ActivitySeeder::class,
            UserSessionSeeder::class,
        ]);
    }
}
