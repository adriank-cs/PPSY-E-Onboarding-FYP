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
            ],
            [
                'CompanyID' => '2',
                'Name' => 'YG Entertainment',
                'Industry' => 'Media and Entertainment',
                'Address' => 'Seoul, South Korea',
                'Website' => 'https://www.facebook.com',
                'created_at' => '2024-01-26 08:42:00',
                'updated_at' => '2024-01-26 08:42:00',
            ],
            [
                'CompanyID' => '3',
                'Name' => 'Apple',
                'Industry' => 'Technology',
                'Address' => '1 Apple Park Way, Cupertino, CA 95014, USA',
                'Website' => 'https://www.apple.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
            ],
            [
                'CompanyID' => '4',
                'Name' => 'Microsoft',
                'Industry' => 'Technology',
                'Address' => 'One Microsoft Way, Redmond, WA 98052, USA',
                'Website' => 'https://www.microsoft.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
            ],
            [
                'CompanyID' => '5',
                'Name' => 'Amazon',
                'Industry' => 'Technology',
                'Address' => '410 Terry Ave N, Seattle, WA 98109, USA',
                'Website' => 'https://www.amazon.com',
                'created_at' => '2024-01-26 08:16:00',
                'updated_at' => '2024-01-26 08:16:00',
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

        //Call other seeder classes
        $this->call([
            OnboardingModuleSeeder::class,
            ActivitySeeder::class,
        ]);
    }
}
