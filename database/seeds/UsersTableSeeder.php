<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create startup users
        factory(App\User::class)
            ->create([
                'email' => 'startup@example.com',
                'type' => 'startup',
            ])
            ->each(function ($user) {
                factory(\App\Models\Startup::class)->create([
                    'owner_id' => $user->id,
                ]);
            });
        factory(App\User::class, 20)
            ->create(['type' => 'startup'])
            ->each(function ($user) {
                factory(\App\Models\Startup::class)->create([
                    'owner_id' => $user->id,
                ]);
            });

        // create mentors
        factory(App\User::class)->create([
            'email' => 'mentor@example.com',
            'type' => 'mentor',
            'meta' => [
                'is_initial' => false,
            ],
        ]);
        factory(App\User::class, 10)->create(['type' => 'mentor']);

        // create investors
        factory(App\User::class)->create([
            'email' => 'investor@example.com',
            'type' => 'investor',
        ]);
        factory(App\User::class, 10)->create([
            'type' => 'investor',
        ]);
    }
}
