<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Players;
use App\Models\Tours;
use App\Models\Games;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class formController extends Controller
{
    

    public function newPlayer(Request $request) {
        $userId = Auth::id();
         

        $input = $request->all();
        $name = $input['name'];
        $family = $input['family'];
        $city = $input['city'];
      
        Players::create([
            'name_player' => $name, 'family_player' => $family, 'city' => $city,
             'plaer_from_id' => $userId,
        ]);


        // $players = Players::all();
        // return view('players', compact('players'));

        return back()->withInput();
       // return redirect('/players');
             }

                
        public function deletePlayer(Request $request) {
            $input = $request->all();
            $id = $input['id'];
            Players::where('id',$id)->delete();
            Games::where('player_id',$id)->delete();
            return back()->withInput();

        }

        public function updatePlayer(Request $request) {
            $input = $request->all();
            $id = $input['id'];
            $name = $input['name'];
            $family = $input['family'];
            $city = $input['city'];
            $Players = Players::where('id', $id )->update([
                'name_player' => $name, 'family_player' => $family, 'city' => $city, 
            ]);
            return back()->withInput();

        }

        public function findPlayer(Request $request) {
            $input = $request->all();
            $find='';
            $find = $input['find'];
            
           
            $players = Players::where('name_player', 'like', '%'.$find.'%')->orWhere('family_player', 'like', '%'.$find.'%')->orWhere('city', 'like', '%'.$find.'%')->
            where('plaer_from_id', $userId)->paginate(3);
            //переделать возваращение на страницу
            return view('players', compact('players','find'));
        }

        public function newTour(Request $request) {
            $userId = Auth::id(); 

             $input = $request->all();
            $name = $input['name'];  
            $date = $input['date']; 
            $link = $input['link'];
            $city = $input['city']; 
            
            $count =$input['count'];
            
            $players = Players::where('plaer_from_id',$userId)->orderBy('family_player')->get();

            $tour = Tours::create([
                'name_tour' => $name, 'date' => $date,'city_tour' => $city,
                'link' => $link,'tour_user_id' => $userId,           
            ]);
            $tourId=$tour->id;
            
            if (isset($input['parser']))  
          return view('parser.newTour', compact('count','players','tourId','tour'));
           // return view('parser.newTour');
            else 
           return view('newGames', compact('count','players','tourId','tour'));
          // return view('newGames');
        }
        
        public function addGames(Request $request){
            $input = $request->all();
            $userId = Auth::id();
            if (isset($input['tourIdParser'])) {
                $id = $input['tourIdParser'];
                $tour = Tours::where('id',$id)->first();
                return view('parser.newTour', compact('tour'));
            } else 
            {
            $count = $input['count'];
            $tourId = $input['id'];
            $tour = Tours::where('id', $tourId)->first();
            $players = Players::where('plaer_from_id',$userId)->orderBy('family_player')->get();
            return view('newGames', compact('count','players','tourId','tour'));
            }
        }

        public function deleteTour(Request $request) {
            $input = $request->all();
            $id = $input['id'];
            
            Tours::where('id',$id)->delete();
            Games::where('tour_id',$id)->delete();
            return back()->withInput();

        }

        public function insertGames(Request $request) {
            $userId =  Auth::id(); 

            $input = $request->all();
            $max=$input['count'];
            
            for ($i=1; $i<=$max; $i++) {
                if (isset($input['CheckPlayer'.$i]))  {
                    if ($input['CheckPlayer'.$i]=='yes') { 
                        
                        $players = Players::create([
                            'name_player' => $input['name'.$i],
                            'family_player' => $input['fam'.$i],
                            'city' => $input['city'.$i],
                            'plaer_from_id' => $userId
                        ]);
                        $idPlayer = $players->id;

                        if  ($input['user_score'.$i]>$input['player_score'.$i]) { $result=2; }
                        if  ($input['user_score'.$i]==$input['player_score'.$i]) { $result=1; }
                        if  ($input['user_score'.$i]<$input['player_score'.$i]) { $result=0; }


                        $type = '';
                        if (isset($input['playoff'.$i])) {
                           if ($input['playoff'.$i]=='ok') { 
                               $type = 'p';
                           }
                        } else { 
                           $type='r'; 
                        }
                        //тут командные турниры не учитывает
       
                        if (isset($input['ot'.$i])) {
                           if ($input['ot'.$i]=='ok'){ 
                               $type = 'o';
                           }
                       }
                           //овертаймы
                        $games = Games::create([
                            'tour_id' => $input['tourid'], 'user_id' => $userId, 'player_id' => $idPlayer, 'goal_for' => $input['user_score'.$i],
                            'goal_away' =>  $input['player_score'.$i], 'result' => $result, 'type' => $type              
                        ]);
                    }
                } else {
                        if (isset($input['select'.$i])) {
                            $idPlayerUpdate=$input['select'.$i];
                    // --
                    $selectForIdPlayer = Players::where('id', $idPlayerUpdate)->first();                 
                    $idPlayer= $selectForIdPlayer->id;
                      
                    }

               
                if  ($input['user_score'.$i]>$input['player_score'.$i]) { $result=2; }
                if  ($input['user_score'.$i]==$input['player_score'.$i]) { $result=1; }
                if  ($input['user_score'.$i]<$input['player_score'.$i]) { $result=0; }
                 //результат больше меньше
                      
                 $type = '';

                 if (isset($input['playoff'.$i])) {
                    if ($input['playoff'.$i]=='ok') { 
                        $type = 'p';
                    }
                 } else { 
                    $type='r'; 
                 }
                 //тут командные турниры не учитывает

                 if (isset($input['ot'.$i])) {
                    if ($input['ot'.$i]=='ok'){ 
                        $type = 'o';
                    }
                }
                    //овертаймы
                  
                  $games = Games::create([
                        'tour_id' => $input['tourid'], 'user_id' => $userId, 'player_id' => $idPlayer, 'goal_for' => $input['user_score'.$i],
                        'goal_away' =>  $input['player_score'.$i], 'result' => $result, 'type' => $type, 'user_id' => $userId,                 
                    ]);


                }
            }
             return redirect('/');
        }    

        public function deleteOneGame($tourId, Request $request){
        $userId = Auth::id(); 
        $input = $request->all();
        $id = $input['id'];
        Games::where([['id',$id],['user_id',$userId]])->delete();
        return redirect('edit/games/'.$tourId);
        }


        public function updateOneGame($tourId, Request $request){
            $input = $request->all();
            $id = $input['id'];
            $userId = Auth::id(); 
            //запрос
            if ($input['gf']>$input['ga']) $result=2; 
            if ($input['gf']==$input['ga']) $result=1;
            if ($input['gf']<$input['ga']) $result=0; 

            Games::where([['id',$id],['user_id',$userId]])
            ->update([
                'goal_for' => $input['gf'],
                'goal_away' => $input['ga'],
                'type' => $input['type'],
                'result' => $result,
            ]);
            return redirect('edit/games/'.$tourId);
        }


        public function parsGames(Request $request){
            $userId = Auth::id(); 

            $input = $request->all();
            $max=$input['count'];
            for ($i=1; $i<=$max; $i++) {

                if (isset($input['insert'.$i]))  {
                    if ($input['insert'.$i]=='yes') { 

                if (isset($input['CheckPlayer'.$i]))  {
                    if ($input['CheckPlayer'.$i]=='yes') { 
                        
                        $players = Players::create([
                            'name_player' => $input['name'.$i],
                            'family_player' => $input['fam'.$i],
                            'city' => $input['city'.$i],
                            'plaer_from_id' => $userId
                        ]);
                        $idPlayer = $players->id;
                           }  
                        } else {

                            $idPlayer = $input['idPlayer'.$i];
                        }

                        if ($input['user_score'.$i]>$input['player_score'.$i]) $result=2; 
                        if ($input['user_score'.$i]==$input['player_score'.$i]) $result=1;
                        if ($input['user_score'.$i]<$input['player_score'.$i]) $result=0; 

                        $type = 'r';


                   
                        $games = Games::create([
                            'tour_id' => $input['tourid'], 'user_id' => $userId, 'player_id' => $idPlayer, 'goal_for' => $input['user_score'.$i],
                            'goal_away' =>  $input['player_score'.$i], 'result' => $result, 'type' => $type, 'user_id' => $userId,                 
                        ]);

                    }
                }
                       
               
            }
                    return redirect('/tours');

        }


        public function updateUser(Request $request){
            $input = $request->all();
            $userId = Auth::id();
            $allName = $input['name'].' '.$input['family'];
            $email = $input['email'];
            User::where('id', Auth::id())
            ->update(['name' => $allName],
                      ['email' => $email]
                );

                $info['goalFor'] = Games::where('user_id',$userId)->sum('goal_for');
                $info['goalAway'] = Games::where('user_id',$userId)->sum('goal_away');
                $info['games'] =Games::where('user_id',$userId)->count();
                $info['wins'] = Games::where('user_id',$userId)->where('result','2')->get()->count();
                $info['tie'] = Games::where('user_id',$userId)->where('result','1')->get()->count();
                $info['lose'] = Games::where('user_id',$userId)->where('result','0')->get()->count();
                $info['winsOT'] = Games::where('user_id',$userId)->where('result','2')->where('type','o')->get()->count();
                $info['loseOT'] = Games::where('user_id',$userId)->where('result','0')->where('type','o')->get()->count();
                $info['hours'] = round($info['games']/12,2);

                $nameFamily = explode(' ', $allName);
                $name = $nameFamily[0];
                $family = $nameFamily[1];
                $massageUpdate = 'Данные успешно обновлены';
                return view('setting.infoAboutUser', compact('massageUpdate','name','family','info'));
            
          }


          public function updatePassword(Request $request){
            $userId = Auth::id();
            $input = $request->all();
            $userId = Auth::id(); 
            $nameFamily = explode(' ',Auth::user()->name);
            $name = $nameFamily[0];
            $family = $nameFamily[1];
            $oldPass = User::where('id',$userId)->first();
            $oldPass = $oldPass->password;
            $info['goalFor'] = Games::where('user_id',$userId)->sum('goal_for');
            $info['goalAway'] = Games::where('user_id',$userId)->sum('goal_away');
            $info['games'] =Games::where('user_id',$userId)->count();
            $info['wins'] = Games::where('user_id',$userId)->where('result','2')->get()->count();
            $info['tie'] = Games::where('user_id',$userId)->where('result','1')->get()->count();
            $info['lose'] = Games::where('user_id',$userId)->where('result','0')->get()->count();
            $info['winsOT'] = Games::where('user_id',$userId)->where('result','2')->where('type','o')->get()->count();
            $info['loseOT'] = Games::where('user_id',$userId)->where('result','0')->where('type','o')->get()->count();
            $info['hours'] = round($info['games']/12,2);
            if (Hash::check($input['oldPassword'], $oldPass)) {
             
                    if (strlen($input['newPassword']) <= 8) {
                        $massage = 'Минимальная длина пароля - 8 символов';
                        $classCSS = 'error';
                    } else {

                User::where('id', $userId)
                   ->update(['password' => Hash::make($input['newPassword'])],
                );
                $massage = 'Пароль обновлён!';
                $classCSS = 'success';
                     }
                  } else {
                $massage = 'Пароль неверный! ';
                $classCSS = 'error';
                }
            return view('setting.infoAboutUser', compact('massage','name','family', 'classCSS','info'));
          }


            public function parserGamePlayoff(Request $request){
                $userId = Auth::id(); 
                $input = $request->all();
                $max=$input['count'];
                $saveId = 0;
                $idPlayer = 0;
                for ($i=1; $i<=$max; $i++) {
    
                    if (isset($input['insert'.$i]))  {
                        if ($input['insert'.$i]=='yes') { 
    
                    if (isset($input['CheckPlayer'.$i]))  {
                        if ($input['CheckPlayer'.$i]=='yes') { 
                            //??
                            $players = Players::create([
                                'name_player' => $input['name'.$i],
                                'family_player' => $input['fam'.$i],
                                'city' => $input['city'.$i],
                                'plaer_from_id' => $userId
                            ]);
                            $idPlayer = $players->id;
                            $saveId = $idPlayer;
                           
                               }  
                            } else {
                             if ($input['status'.$i]<>'notNew')  {           
                             echo 'нет'.$i.'-'.$idPlayer.'<br>';                              
                             $idPlayer = $input['idPlayer'.$i]; 
                             } 
                            }
                              
                            

                            if ($input['user_score'.$i]>$input['player_score'.$i]) $result=2; 
                            if ($input['user_score'.$i]==$input['player_score'.$i]) $result=1;
                            if ($input['user_score'.$i]<$input['player_score'.$i]) $result=0; 
    
                            $type = $input['type'.$i];
    
    
                         
                            Games::create([
                                'tour_id' => $input['tourid'], 'user_id' => $userId, 'player_id' => $idPlayer, 'goal_for' => $input['user_score'.$i],
                                'goal_away' =>  $input['player_score'.$i], 'result' => $result, 'type' => $type, 'user_id' => $userId,                 
                            ]);
    
                        }
                    }
                           
                   
                }
                        return redirect('/tours');

            }

    }