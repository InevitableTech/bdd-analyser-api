<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consoleUserId = DB::table('users')->insertGetId([
            'firstname' => 'web',
            'lastname' => 'console',
            'email' => '',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ]);

        $userId = DB::table('users')->insertGetId([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => 'its.inevitable@hotmail.com',
            'password_hash' => Hash::make('password'),
            'dob' => '1989/05/10',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ]);

        DB::table('users')->insertGetId([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'email' => Str::random(10) . '@hotmail.com',
            'password_hash' => Hash::make('password'),
            'dob' => '2003/05/10',
            'enabled' => 1,
            'created_at' => new \DateTime()
        ]);

        $organisationId = DB::table('organisations')->insertGetId([
            'name' => 'InevitableTech.uk',
            'created_at' => new \DateTime()
        ]);

        $projectId = DB::table('projects')->insertGetId([
            'name' => 'BDD Analyser',
            'organisation_id' => $organisationId,
            'enabled' => 1,
            'repo_url' => 'https://github.com/InevitableTech/bdd-analyser-api',
            'created_at' => new \DateTime()
        ]);

        DB::table('project_user')->insert([
            'user_id' => $userId,
            'project_id' => $projectId,
            'created_at' => new \DateTime()
        ]);

        DB::table('tokens')->insert([
            'token' => Crypt::encryptString(Str::random(60)),
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => $consoleUserId,
            'policies' => json_encode(['resources' => '*']),
            'created_at' => new \DateTime()
        ]);

        // Can a user have multiple tokens? Yes if the policies differ.
        // Is there such a thing as an app token?
        DB::table('tokens')->insert([
            'token' => Crypt::encryptString('kahlsjdhfjh2h34234k2h4j2j3hk4h2jak=='),
            'expires_on' => new \DateTime('+3 years'),
            'user_id' => $userId,
            'policies' => json_encode([
                'resources' => [
                    '/user' => '*',
                    '/project' => '*',
                    '/analysis' => '*',
                    '/organisation' => '*',
                    '/token' => '*'
                ]
            ]),
            'created_at' => new \DateTime()
        ]);

        DB::table('analysis')->insert([
            'run_at' => new \DateTime('2023-01-01'),
            'rules_version' => 'v1',

            'violations' => '[{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyValidOrderAllowed","file":"testproject/features/cucumber.feature","lineNumber":28,"severity":"1","scenario":"Verification of Login Function","violatingLine":"Then user should see \"My Account\"","rawStep":"Then user should see \"My Account\" ","message":"Expected step to start with keyword \"when\", got \"then\" instead. Are you missing a \"when\" step?","cleanStep":"user should see \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:28"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyValidOrderAllowed","file":"testproject/features/userLogin.feature","lineNumber":35,"severity":"1","scenario":"Unsuccessful login","violatingLine":"Then error message displayed with wrong password","rawStep":"Then error message displayed with wrong password","message":"Expected step to start with keyword \"when\", got \"then\" instead. Are you missing a \"when\" step?","cleanStep":"error message displayed with wrong password","uniqueScenarioId":"testproject/features/userLogin.feature:35"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":8,"severity":"1","scenario":"Create a New User","violatingLine":"When user fills \"registration email textbox\" with \"chitrali.sharma27@gmail.com\"","rawStep":"When user fills \"registration email textbox\" with \"chitrali.sharma27@gmail.com\"  ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user fills \"{string}\" with \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:8"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":9,"severity":"1","scenario":"Create a New User","violatingLine":"And user clicks \"create an account button\"","rawStep":"And user clicks \"create an account button\"  ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user clicks \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:9"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":10,"severity":"1","scenario":"Create a New User","violatingLine":"And user enters the following details","rawStep":"And user enters the following details ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters the following details","uniqueScenarioId":"testproject/features/cucumber.feature:10"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":15,"severity":"1","scenario":"Create a New User","violatingLine":"And user clicks \"register button\"","rawStep":"And user clicks \"register button\"","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user clicks \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:15"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":18,"severity":"1","scenario":"User does not follow form validations","violatingLine":"When user enters wrong characters","rawStep":" When user enters wrong characters","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters wrong characters","uniqueScenarioId":"testproject/features/cucumber.feature:18"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":19,"severity":"1","scenario":"User does not follow form validations","violatingLine":"Then error message displayed with invalid password","rawStep":"  Then error message displayed with invalid password","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"error message displayed with invalid password","uniqueScenarioId":"testproject/features/cucumber.feature:19"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/dashboard.feature","lineNumber":20,"severity":"1","scenario":"User does not follow form validations","violatingLine":"And user returns back on registration page","rawStep":"And user returns back on registration page","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user returns back on registration page","uniqueScenarioId":"testproject/features/dashboard.feature:20"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":24,"severity":"1","scenario":"Verification of Login Function","violatingLine":"Given user on the Login Page","rawStep":"Given user on the Login Page","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user on the Login Page","uniqueScenarioId":"testproject/features/cucumber.feature:24"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":25,"severity":"1","scenario":"Verification of Login Function","violatingLine":"And user enters \"email address\" with \"chitrali.sharma27@gmail.com\"","rawStep":"And user enters \"email address\" with \"chitrali.sharma27@gmail.com\" ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters \"{string}\" with \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:25"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/dashboard.feature","lineNumber":26,"severity":"1","scenario":"Verification of Login Function","violatingLine":"And user enters \"password\" with \"Inquiry@1234\"","rawStep":"And user enters \"password\" with \"Inquiry@1234\"  ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters \"{string}\" with \"{string}\"","uniqueScenarioId":"testproject/features/dashboard.feature:26"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":27,"severity":"1","scenario":"Verification of Login Function","violatingLine":"And user click \"log in\" button","rawStep":"And user click \"log in\" button","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user click \"{string}\" button","uniqueScenarioId":"testproject/features/cucumber.feature:27"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":28,"severity":"1","scenario":"Verification of Login Function","violatingLine":"Then user should see \"My Account\"","rawStep":"Then user should see \"My Account\" ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user should see \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:28"}]',

            'summary' => '{"files":6, "violations": 7, "backgrounds":{"testing/vendor/forceedge01/bdd-analyser/testproject/features/cucumber.feature2":"testing/vendor/forceedge01/bdd-analyser/testproject/features/cucumber.feature2","testing/vendor/forceedge01/bdd-analyser/testproject/features/no-scenarios.feature1":"testing/vendor/forceedge01/bdd-analyser/testproject/features/no-scenarios.feature1","testing/vendor/forceedge01/bdd-analyser/testproject/features/test.feature7":"testing/vendor/forceedge01/bdd-analyser/testproject/features/test.feature7"}}',

            'active_steps' => '[
                "I am on the login page",
                "I do something",
                "I submit the details",
                "I should see bla",
                "user fills \"{string}\" with \"{string}\"",
                "user clicks \"{string}\"",
                "user enters the following details",
                "user enters wrong characters",
                "error message displayed with invalid password",
                "user returns back on registration page",
                "user on the Login Page",
                "user enters \"{string}\" with \"{string}\"",
                "user click \"{string}\" button",
                "user should see \"{string}\"",
                "user clicks \"{string}\" button",
                "error message displayed with wrong password",
                "user returns back on login page",
                "I fill in the credentials:",
                "I should whatever",
                "I am here",
                "I fill in the credentials",
                "I should be on the dashboard",
                "they submit the details \"{string}\" and \"{string}\" and \"{string}\"",
                "they submit the details \"{string}\"",
                "he should whatever",
                "they submit the details \"{string}\" and \"{string}\"",
                "I should whatever \"{string}\""
            ]',

            'active_rules' => '["Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoUrlInSteps",{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoLongScenarios":{"args":[10]}},"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoCommentedOutSteps","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\DiscouragedTags","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyValidOrderAllowed","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoSelectorsInSteps",{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed":{"args":["given","when","then","and","but"]}},{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NotTooManyScenariosPerFeature":{"args":[10]}},{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NotTooManyExamplesPerScenario":{"args":[3]}},"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoDuplicateScenarios","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoScenarioWithoutDescription","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoFeatureWithoutNarrative","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\AllStepsInlineEachFile","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\AllScenariosInlineEachFile",{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\MinimumLengthScenario":{"args":[2]}},"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoEmptyFeature","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoConjunctionInSteps","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoScenarioWithoutAssertion"]',

            'severities' => '["0","1","2","3","4"]',
            'branch' => 'main',
            'commit_hash' => '7da73f3de7394a273587f872065f75a89c68066e',
            'created_at' => new \DateTime(),
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);

        DB::table('analysis')->insert([
            'run_at' => new \DateTime('2023-03-10'),
            'rules_version' => 'v1',

            'violations' => '[{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyValidOrderAllowed","file":"testproject/features/cucumber.feature","lineNumber":28,"severity":"1","scenario":"Verification of Login Function","violatingLine":"Then user should see \"My Account\"","rawStep":"Then user should see \"My Account\" ","message":"Expected step to start with keyword \"when\", got \"then\" instead. Are you missing a \"when\" step?","cleanStep":"user should see \"{string}\"","uniqueScenarioId":"testproject/features/login.feature:28"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyValidOrderAllowed","file":"testproject/features/cucumber.feature","lineNumber":35,"severity":"1","scenario":"Unsuccessful login","violatingLine":"Then error message displayed with wrong password","rawStep":"Then error message displayed with wrong password","message":"Expected step to start with keyword \"when\", got \"then\" instead. Are you missing a \"when\" step?","cleanStep":"error message displayed with wrong password","uniqueScenarioId":"testproject/features/login.feature:35"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":8,"severity":"1","scenario":"Create a New User","violatingLine":"When user fills \"registration email textbox\" with \"chitrali.sharma27@gmail.com\"","rawStep":"When user fills \"registration email textbox\" with \"chitrali.sharma27@gmail.com\"  ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user fills \"{string}\" with \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:8"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":9,"severity":"1","scenario":"Create a New User","violatingLine":"And user clicks \"create an account button\"","rawStep":"And user clicks \"create an account button\"  ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user clicks \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:9"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":10,"severity":"1","scenario":"Create a New User","violatingLine":"And user enters the following details","rawStep":"And user enters the following details ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters the following details","uniqueScenarioId":"testproject/features/cucumber.feature:10"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":15,"severity":"1","scenario":"Create a New User","violatingLine":"And user clicks \"register button\"","rawStep":"And user clicks \"register button\"","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user clicks \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:15"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/reset-password.feature","lineNumber":18,"severity":"1","scenario":"User does not follow form validations","violatingLine":"When user enters wrong characters","rawStep":" When user enters wrong characters","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters wrong characters","uniqueScenarioId":"testproject/features/cucumber.feature:18"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":19,"severity":"1","scenario":"User does not follow form validations","violatingLine":"Then error message displayed with invalid password","rawStep":"  Then error message displayed with invalid password","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"error message displayed with invalid password","uniqueScenarioId":"testproject/features/cucumber.feature:19"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":20,"severity":"1","scenario":"User does not follow form validations","violatingLine":"And user returns back on registration page","rawStep":"And user returns back on registration page","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user returns back on registration page","uniqueScenarioId":"testproject/features/cucumber.feature:20"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":24,"severity":"1","scenario":"Verification of Login Function","violatingLine":"Given user on the Login Page","rawStep":"Given user on the Login Page","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user on the Login Page","uniqueScenarioId":"testproject/features/cucumber.feature:24"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":25,"severity":"1","scenario":"Verification of Login Function","violatingLine":"And user enters \"email address\" with \"chitrali.sharma27@gmail.com\"","rawStep":"And user enters \"email address\" with \"chitrali.sharma27@gmail.com\" ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters \"{string}\" with \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:25"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/cucumber.feature","lineNumber":26,"severity":"1","scenario":"Verification of Login Function","violatingLine":"And user enters \"password\" with \"Inquiry@1234\"","rawStep":"And user enters \"password\" with \"Inquiry@1234\"  ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user enters \"{string}\" with \"{string}\"","uniqueScenarioId":"testproject/features/profile.feature:26"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/history.feature","lineNumber":27,"severity":"1","scenario":"Verification of Login Function","violatingLine":"And user click \"log in\" button","rawStep":"And user click \"log in\" button","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user click \"{string}\" button","uniqueScenarioId":"testproject/features/history.feature:27"},{"rule":"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed","file":"testproject/features/history.feature","lineNumber":28,"severity":"1","scenario":"Verification of Login Function","violatingLine":"Then user should see \"My Account\"","rawStep":"Then user should see \"My Account\" ","message":"Steps should be written in first person pov only. This makes the scenario more relatable to yourself and encourages you to put yourself into the users position when writing or reading the scenario.","cleanStep":"user should see \"{string}\"","uniqueScenarioId":"testproject/features/cucumber.feature:28"}]',

            'summary' => '{"files":7, "violations": 14, "backgrounds":{"testing/vendor/forceedge01/bdd-analyser/testproject/features/cucumber.feature2":"testing/vendor/forceedge01/bdd-analyser/testproject/features/cucumber.feature2","testing/vendor/forceedge01/bdd-analyser/testproject/features/no-scenarios.feature1":"testing/vendor/forceedge01/bdd-analyser/testproject/features/no-scenarios.feature1","testing/vendor/forceedge01/bdd-analyser/testproject/features/test.feature7":"testing/vendor/forceedge01/bdd-analyser/testproject/features/test.feature7"}}',

            'active_steps' => '[
                "I am on the login page",
                "I do something",
                "I submit the details",
                "I should see bla",
                "user fills \"{string}\" with \"{string}\"",
                "user clicks \"{string}\"",
                "user enters the following details",
                "user enters wrong characters",
                "error message displayed with invalid password",
                "user returns back on registration page",
                "user on the Login Page",
                "user enters \"{string}\" with \"{string}\"",
                "user click \"{string}\" button",
                "user should see \"{string}\"",
                "user clicks \"{string}\" button",
                "error message displayed with wrong password",
                "user returns back on login page",
                "I fill in the credentials:",
                "I should whatever",
                "I am here",
                "I fill in the credentials",
                "I should be on the dashboard",
                "they submit the details \"{string}\" and \"{string}\" and \"{string}\"",
                "they submit the details \"{string}\"",
                "he should whatever",
                "they submit the details \"{string}\" and \"{string}\"",
                "I should whatever \"{string}\""
            ]',

            'active_rules' => '["Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoUrlInSteps",{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoLongScenarios":{"args":[10]}},"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoCommentedOutSteps","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\DiscouragedTags","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyValidOrderAllowed","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoSelectorsInSteps",{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\OnlyFirstPersonLanguageAllowed":{"args":["given","when","then","and","but"]}},{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NotTooManyScenariosPerFeature":{"args":[10]}},{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NotTooManyExamplesPerScenario":{"args":[3]}},"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoDuplicateScenarios","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoScenarioWithoutDescription","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoFeatureWithoutNarrative","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\AllStepsInlineEachFile","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\AllScenariosInlineEachFile",{"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\MinimumLengthScenario":{"args":[2]}},"Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoEmptyFeature","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoConjunctionInSteps","Forceedge01\\\BDDStaticAnalyserRules\\\Rules\\\NoScenarioWithoutAssertion"]',

            'severities' => '["0","1","2"]',
            'branch' => 'main',
            'commit_hash' => '7da73f3de7394a273587f872065f75a89c68066e',
            'created_at' => new \DateTime(),
            'project_id' => $projectId,
            'user_id' => $userId,
        ]);
    }
}
