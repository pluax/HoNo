<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
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
        $count = Tours::where([['tour_user_id', $userId],['name_tour', 'like', '%'.$find.'%']])->orderByDesc('date')->count();
        $tours = Tours::where([['tour_user_id', $userId],['name_tour', 'like', '%'.$find.'%']])->orderByDesc('date')->paginate(50);
        foreach ($tours as $tour){
          $tour->date = Carbon::parse($tour->date)->format('d.m.Y'); 
        }
        return view('tours', compact('tours','find','count'));

    }
      
    public function index(Request $request){
      $userId = Auth::id();  
      $input = $request->all();
      $find = isset($input['find']) ? $input['find'] : '';
      
        $players = DB::table('players')
        ->select('family_player','name_player','games.player_id','city',
        DB::raw('sum(`goal_for`) as "gf", sum(`goal_away`) as "ga",sum(`goal_for`)-sum(`goal_away`) as "pm"'),
        DB::raw('(select count(`id`) from `games` 
        where `result`=2 and `player_id`= `players`.`id` and `user_id`='.$userId.') as w'),
        DB::raw('(select count(`id`) from `games` 
        where `result`=1 and `player_id`= `players`.`id` and `user_id`='.$userId.') as t'),
        DB::raw('(select count(`id`) from `games` 
        where `result`=0 and `player_id`= `players`.`id` and `user_id`='.$userId.') as l'),
        DB::raw('(select count(player_id) from `games` 
        where `player_id`= `players`.`id` and `user_id`='.$userId.') as count')
        )
        ->where('plaer_from_id',$userId)
        ->where(function($q) use ($find){
          $q->where('family_player', 'like', '%'.$find.'%')
            ->orWhere('name_player', 'like', '%'.$find.'%')
            ->orWhere('city', 'like', '%'.$find.'%');
      })
        //поиск ??
        
        ->leftJoin('games', 'player_id', '=' ,'players.id')
        ->leftJoin('tours', 'tour_id', '=' ,'tours.id')
       
        ->groupBy( 'games.player_id', 'players.family_player', 'players.city', 'players.name_player', 'players.id')
        ->orderByDesc(DB::raw('(select count(player_id) from `games` 
        where `player_id`= `players`.`id` and `user_id`='.$userId.')'))
        ->paginate(50);
     
       
      return view('stats.games', compact('players','find'));
      }
    
    public function playerOne($id, Request $request){
      $idPlayer = Auth::id();
      $count =  Games::where([['player_id',$id],['user_id',$idPlayer]])
      ->leftJoin('tours', 'games.tour_id', '=', 'tours.id')->count();
      $games =  Games::where([['player_id',$id],['user_id',$idPlayer]])
      ->leftJoin('tours', 'games.tour_id', '=', 'tours.id')->orderByDesc('tours.date')->orderByDesc('games.id')
      ->paginate(50);

      if ($games->count()==0) {
        return back()->withInput();
      } 
      else 
      {
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
           $game->date = Carbon::parse($game->date)->format('d.m.Y'); 
      }
        $avgFor = round($goalFor/$games->count(), 2);
        $avgAway = round($goalAway/$games->count(), 2);
      return view('stats.player', compact('games','player','wins','tie','lose','goalFor','goalAway','avgFor','avgAway','count'));
        }
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


    public function editTours(Request $request){
      $userId = Auth::id();
      $input = $request->all();
      $find = isset($input['find']) ? $input['find'] : '';
      $count = Tours::where([['tour_user_id', $userId],['name_tour', 'like', '%'.$find.'%']])->count();
      $tours = Tours::where([['tour_user_id', $userId],['name_tour', 'like', '%'.$find.'%']])->orderByDesc('date')->paginate(50);
      foreach ($tours as $tour){
        $tour->date = Carbon::parse($tour->date)->format('d.m.Y'); 
      }
      return view('setting.settingTour', compact('tours','find','count'));
    }

    public function toursEditGames(Request $request){
      $userId =  Auth::id();
      $input = $request->all();
      $find = isset($input['find']) ? $input['find'] : '';
      $count = Tours::where('tour_user_id', $userId)->where('name_tour','like','%'.$find.'%' )->count();
      $tours = Tours::where('tour_user_id', $userId)->where('name_tour','like','%'.$find.'%' )->orderByDesc('date')->paginate(50);
      foreach ($tours as $tour){
        $tour->date = Carbon::parse($tour->date)->format('d.m.Y'); 
      }
      return view('insertExistTour', compact('tours','find','count'));
    }

    public function oneTour($id){
      $userId = Auth::id();

      $games = Games::select('tour_id','user_id','player_id','goal_for','goal_away','result','type','family_player','name_player','players.id')
      ->where([['tour_id',$id],['user_id',$userId]])
      ->leftJoin('players', 'games.player_id', '=', 'players.id')
      ->leftJoin('tours', 'games.tour_id', '=', 'tours.id')
      ->get();     

      if ($games->count()==0) {
        return back()->withInput();
      } 
      else 
      {

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

        if ($game->type=='o') $game->ot = '(OT)'; else $game->ot = '';

        if ($game->type=='r')  
         $game->backgroundType = 'background:#fff;'; 
        else 
         $game->backgroundType = 'background:#dcdcdc;';
      } 
      $avgFor = round($goalFor/$games->count(), 2);
      $avgAway = round($goalAway/$games->count(), 2);
 
      $tour->date = Carbon::parse($tour->date)->format('d.m.Y'); 
      
      return view('stats.oneTour', compact('games','tour','wins','tie','lose','goalFor','goalAway','avgFor','avgAway'));
     }
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
        $userId = Auth::id();
        
        $nameFamily = explode(' ',Auth::user()->name);
        $name = $nameFamily[0];
        $family = $nameFamily[1];
        $info['goalFor'] = Games::where('user_id',$userId)->sum('goal_for');
        $info['goalAway'] = Games::where('user_id',$userId)->sum('goal_away');
        $info['games'] =Games::where('user_id',$userId)->count();
        $info['wins'] = Games::where('user_id',$userId)->where('result','2')->get()->count();
        $info['tie'] = Games::where('user_id',$userId)->where('result','1')->get()->count();
        $info['lose'] = Games::where('user_id',$userId)->where('result','0')->get()->count();
        $info['winsOT'] = Games::where('user_id',$userId)->where('result','2')->where('type','o')->get()->count();
        $info['loseOT'] = Games::where('user_id',$userId)->where('result','0')->where('type','o')->get()->count();
        $info['hours'] = round($info['games']/12,2);
        //$goalAway 
        return view('setting.infoAboutUser', compact('name','family','info'));
      }

  


    

}
