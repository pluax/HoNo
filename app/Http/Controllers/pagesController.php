<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tours;
use App\Models\Games;
use App\Models\Players;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;

class pagesController extends Controller
{
    public function tours(Request $request){
        $userId = Auth::id(); 
        $input = $request->all();
        $find = isset($input['find']) ? $input['find'] : '';
        $tours = Tours::where([['tour_user_id', $userId],['name_tour', 'like', '%'.$find.'%']])->orderByDesc('date')->get();
        return view('tours', compact('tours','find'));

    }
      //ДЮОАВЛЕНИЕ ДОПИЛИТЬ!!!!!
    public function index(Request $request){
      $userId = Auth::id();  
      $input = $request->all();
      $find = isset($input['find']) ? $input['find'] : '';
     
      
      $players = DB::select("select `player_id`, `family_player`, `name_player`, `city`, 
       count(player_id) as 'count', (select count(`id`) from `games` 
       where (`result`=2 and `player_id`= `players`.`id` and `user_id`=".$userId." )) AS 'w',(select count(`id`) 
       from `games` where (`result`='1' and `player_id`= `players`.`id` and `user_id`=".$userId." )) as 't',
       (select count(`id`) from `games` where (`result`='0' and `player_id`= `players`.`id` and `user_id`=".$userId." ))
        as 'l', sum(`goal_for`) as 'gf', sum(`goal_away`) as 'ga',sum(`goal_for`)-sum(`goal_away`)
        as 'pm' from `games` left join `players` on (`player_id` = `players`.`id`) left join `tours`
        on (`tour_id`=`tours`.`id`) where (((`family_player` like '%".$find."%') or (`name_player` like '%".$find."%') or (`city` like '%".$find."%')) and (`user_id`=".$userId.")) group by `games`.`player_id`, `players`.`family_player`, `players`.`city`, `players`.`name_player`, `players`.`id`
        order by count(`player_id`) desc");
      
      return view('stats.games', compact('players','find'));
      }
    
    public function playerOne($id, Request $request){
      $idPlayer = Auth::id();
      $games =  Games::where([['player_id',$id],['user_id',$idPlayer]])
      ->leftJoin('tours', 'games.tour_id', '=', 'tours.id')->orderByDesc('tours.date')->orderByDesc('games.id')
      ->get();

      $player = Players::where('id', $id)->first();

      $wins = Games::where([['player_id',$id],['user_id',$idPlayer]])->where('result','2')->get()->count();
      $tie = Games::where([['player_id',$id],['user_id',$idPlayer]])->where('result','1')->get()->count();
      $lose = Games::where([['player_id',$id],['user_id',$idPlayer]])->where('result','0')->get()->count();

      $goalFor = Games::where([['player_id',$id],['user_id',$idPlayer]])->get()->sum('goal_for');
      $goalAway = Games::where([['player_id',$id],['user_id',$idPlayer]])->get()->sum('goal_away');
      foreach ($games as $game){
          if ($game->type=='r')  
           $game->background = 'background:#fff;'; 
          else 
           $game->background = 'background:#dcdcdc;';
      }

      return view('stats.player', compact('games','player','wins','tie','lose','goalFor','goalAway'));
     }


    public function players(Request $request){
      $input = $request->all();
      $userId = Auth::id(); 
      $find = isset($input['find']) ? $input['find'] : '';
    
      

      $players = Players::where('plaer_from_id','=',$userId)
     
      ->where(function($q) use ($find){
        $q->where('family_player', 'like', '%'.$find.'%')
          ->orWhere('name_player', 'like', '%'.$find.'%')
          ->orWhere('city', 'like', '%'.$find.'%');
    })
     ->paginate(6);

     
      //$input = $request->all();
 

            


          
  
      //переделать возваращение на страницу
      return view('players', compact('players','find'));
    }


    public function editTours(){
      $userId = Auth::id();


      $tours = Tours::where('tour_user_id', $userId)->orderByDesc('date')->get();

      return view('setting.settingTour', compact('tours'));
    }

    public function toursEditGames(Request $request){
      $userId =  Auth::id();
      $input = $request->all();
      $find = isset($input['find']) ? $input['find'] : '';
      $tours = Tours::where('tour_user_id', $userId)->where('name_tour','like','%'.$find.'%' )->orderByDesc('date')->get();
      return view('insertExistTour', compact('tours','find'));
    }

    public function oneTour($id){
      $userId = Auth::id();

      $games = Games::select('tour_id','user_id','player_id','goal_for','goal_away','result','type','family_player','name_player','players.id')
      ->where([['tour_id',$id],['user_id',$userId]])
      ->leftJoin('players', 'games.player_id', '=', 'players.id')
      ->leftJoin('tours', 'games.tour_id', '=', 'tours.id')
      ->get();     

      foreach ($games as $game) {
        if  ($game->result==2) $game->background = "background:#d0f0c0;";
        if  ($game->result==0) $game->background = "background:#ff9090;";
        if  ($game->result==1) $game->background = "background:white;";

        if  ($game->type=='r') $game->typeText = "Турнир";
        if  (($game->type=='p') or ($game->type=='o')) $game->typeText = "play-off";


      }

      $wins = Games::where([['tour_id',$id],['user_id',$userId]])->where('result','2')->get()->count();
      $tie = Games::where([['tour_id',$id],['user_id',$userId]])->where('result','1')->get()->count();
      $lose = Games::where([['tour_id',$id],['user_id',$userId]])->where('result','0')->get()->count();

      $goalFor = Games::where([['tour_id',$id],['user_id',$userId]])->get()->sum('goal_for');
      $goalAway = Games::where([['tour_id',$id],['user_id',$userId]])->get()->sum('goal_away');

      $tour= Tours::where('id',$id)->first();
      foreach ($games as $game){
        if ($game->type=='r')  
         $game->backgroundType = 'background:#fff;'; 
        else 
         $game->backgroundType = 'background:#dcdcdc;';
      } 
      return view('stats.oneTour', compact('games','tour','wins','tie','lose','goalFor','goalAway'));
    }
    public function editOneTour(Request $request){
      $input = $request->all();
      $id = $input['id'];
      $tour = Tours::where('id',$id)->first();
      return view('setting.editTour', compact('tour'));
    }

    public function updateTour(Request $request){
      $input = $request->all();
      $id = $input['id'];
   
      Tours::where('id', $id)->update([
        'name_tour' => $input['name'],
        'date' =>  $input['date'],
        'link' =>  $input['link'],
        'city_tour' => $input['city']
      ]);

      return redirect('/edit');
    }
    
    public function editGamesTour($id){
      $userId = Auth::id();
      $games = Games::select('tour_id','user_id','player_id','goal_for','goal_away','result','type','family_player','name_player','players.id','games.id as gameId')
      ->where([['tour_id',$id],['user_id',$userId]])
      ->leftJoin('players', 'games.player_id', '=', 'players.id')
      ->leftJoin('tours', 'games.tour_id', '=', 'tours.id')
      ->get();     

      $tour= Tours::where('id',$id)->first();
          foreach ($games as $game) {
            if  ($game->result==2) $game->background = "background:#d0f0c0;";
            if  ($game->result==0) $game->background = "background:#ff9090;";
            if  ($game->result==1) $game->background = "background:white;";
          }
    
      return view('setting.updateGamesInTour', compact('games','tour'));
    }
      
      public function info(){
       
        
        $nameFamily = explode(' ',Auth::user()->name);
        $name = $nameFamily[0];
        $family = $nameFamily[1];
        return view('setting.infoAboutUser', compact('name','family'));
      }

  


    

}
