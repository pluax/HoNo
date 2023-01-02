<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Players;
use App\Models\Tours;
use App\Models\Games;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         
        $user = User::create([
            'name' => 'Иван Петров',
            'email' => 'usertest@test.com',
            'password' => Hash::make('12345678'),
        ]);

        $userId = $user->id; 
    
        $idPlayer1 = Players::max('id')+1;
        $idPlayer2 = Players::max('id')+2;
        $idPlayer3 = Players::max('id')+3;

      Players::create([
            'id' => $idPlayer1,
           'name_player' => 'Иван',
           'family_player' => 'Иванов',
           'city' => 'Москва',
           'plaer_from_id' => $userId
       ]);
       Players::create([
            'id' => $idPlayer2,
            'name_player' => 'Максим',
            'family_player' => 'Петров',
            'city' => 'Санкт-Перербург',
            'plaer_from_id' => $userId
       ]);
       Players::create([
            'id' => $idPlayer3,
            'name_player' => 'Денис',
            'family_player' => 'Кузнецов',
            'city' => 'Казань',
            'plaer_from_id' => $userId
         ]);

           $tourId1  =  Tours::max('id')+1;
           $tourId2  =  Tours::max('id')+2;

         Tours::create([
            'id' => $tourId1,
            'name_tour' => 'Чемпионат Москвы',
            'date' => '2019-05-26',
            'city_tour' => 'Москва',
            'link' => 'https://laravel.su/',
            'tour_user_id' => $userId
         ]);
          Tours::create([
            'id' => $tourId2,
            'name_tour' => 'Чемпионат Казани',
            'date' => '2022-01-13',
            'city_tour' => 'Москва',
            'link' => 'https://laravel.su/',
            'tour_user_id' => $userId
         ]);

        Games::create([
         'tour_id' => $tourId1,
         'user_id' => $userId,
         'player_id' =>  $idPlayer1,
         'goal_for' => 4,
         'goal_away' => 2,
         'result' => 2,
         'type' => 'r'
         ]);
         
        Games::create([
            'tour_id' => $tourId1,
            'user_id' => $userId,
            'player_id' =>  $idPlayer2,
            'goal_for' => 3,
            'goal_away' => 3,
            'result' => 1,
            'type' => 'r'

             ]);
    
        Games::create([

            'tour_id' => $tourId1,
            'user_id' => $userId,
            'player_id' =>  $idPlayer1,
            'goal_for' => 1,
            'goal_away' => 2,
            'result' => 0,
            'type' => 'p'

        ]);
        
    
        Games::create([

            'tour_id' => $tourId2,
            'user_id' => $userId,
            'player_id' =>  $idPlayer1,
            'goal_for' => 5,
            'goal_away' => 2,
            'result' => 1,
            'type' => 'r'

        ]);
        
        Games::create([

            'tour_id' => $tourId2,
            'user_id' => $userId,
            'player_id' =>  $idPlayer2,
            'goal_for' => 2,
            'goal_away' => 3,
            'result' => 0,
            'type' => 'o'

        ]);
    

    }
}
