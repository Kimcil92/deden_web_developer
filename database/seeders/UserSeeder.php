<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => Str::uuid(),
                'name' => 'Deden Setiawan',
                'email' => 'admin@admin.com',
                'phone_number' => '082293406492',
                'address' => 'Jl. Raya Pekalongan No. 1 Pekalongan',
                'sim' => '1234567890',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role' => 'Admin',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'User',
                'email' => 'user@user.com',
                'phone_number' => '08123456789',
                'address' => 'Jl. Raya Pekalongan No. 1 Pekalongan',
                'sim' => '2345678901',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role' => 'User',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            $attachments = collect([
                Attachment::query()->create([
                    'filename' => 'anonim',
                    'path' => env('APP_URL') . '/storage/img/default_pic/anonim.png',
                    'mime_type' => 'image/png',
                    'extension' => 'png',
                    'group' => 'profile',
                ])
            ]);

            $user->attachments()->attach($attachments->pluck('id'));

            // Cek peran pengguna
            if ($userData['role'] === 'Admin') {
                // Jika peran adalah Admin, tambahkan role Admin menggunakan Spatie Permission
                $role = Role::firstOrCreate(['name' => 'Admin']);
                $user->assignRole($role);
            } elseif ($userData['role'] === 'User') {
                // Jika peran adalah User, tambahkan role User menggunakan Spatie Permission
                $role = Role::firstOrCreate(['name' => 'User']);
                $user->assignRole($role);
            }
        }

        $product = Product::create([
            'id' => Str::uuid(),
            'product_model' => 'Asus ROG Zephyrus',
            'product_type' => 'Asus ROG Zephyrus',
            'police_number' => 'Asus ROG Zephyrus',
            'product_price' => 15000000,
            'is_active' => 1,
            'user_id' => $users[0]['id'],
        ]);

        $attachmentProduct = collect([
            Attachment::query()->create([
                'filename' => 'rog',
                'path' => env('APP_URL') . '/storage/img/product/rog.png',
                'mime_type' => 'image/png',
                'extension' => 'png',
                'group' => 'product',
            ])
        ]);


        $product->attachments()->attach($attachmentProduct->pluck('id'));
    }
}
