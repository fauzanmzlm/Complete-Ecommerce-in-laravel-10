<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
            'description'=>"Welcome to the Unisza Sport Equipment Booking System! This platform is designed to facilitate the seamless booking of sports equipment for both students and staff. Whether you're preparing for a friendly match or organizing a sports event, our system allows you to reserve the equipment you need at your preferred date and time. Unlock the convenience of the Unisza Sport Equipment Booking System with its user-friendly interface and key features. Easily browse through a diverse selection of sports equipment ready for booking. Submit your booking requests, specifying essential details such as date, time, and equipment preference. Our dedicated admins will promptly review and respond, either approving or denying your request based on availability. Stay in the loop with instant notifications providing real-time updates on the status of your bookings. Additionally, manage your booking history and profile effortlessly through the accessible interface, ensuring a seamless experience for all your sports equipment needs. Enjoy the convenience of reserving sports equipment hassle-free. Start exploring and booking today!",
            'short_des'=>"Reserve sports equipment effortlessly with the Unisza Sport Equipment Booking System. Submit requests, get quick approvals, and stay updated on your bookings. Explore now!",
            'photo'=>"/storage/photos/1/blog3.jpg",
            'logo'=>'/storage/photos/1/LOGO.jpg',
            'address'=>"Universiti Sultan Zainal Abidin Kampus Besut, 22200 Besut, Terengganu Darul Iman, Malaysia",
            'email'=>"admin@usebs.com",
            'phone'=>"+609993333",
        );
        DB::table('settings')->insert($data);
    }
}
