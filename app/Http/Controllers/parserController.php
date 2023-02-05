<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Players;
use App\Models\Tours;
use Illuminate\Support\Facades\Auth;


class parserController extends Controller
{
 
    public function parser(Request $request){
      $input = $request->all();
      $tourId = $input['tourId'];
      $link = $input['link'];  
      $tour = Tours::where('id',$tourId)->first();
      $htmlCode = file_get_contents($link);
      $user =  explode(' ',Auth::user()->name);
      $name = $user[0];
      $family = $user[1];
      $userId = Auth::id(); 
      $status = '';

      $player = $name.' '.$family;


      if (strpos($htmlCode, '>Сетки</a></p>')==0)
        {

      $htmlAll = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $htmlCode);
      $htmlAll = str_replace(array('<span class="ranks-circle" style="background-color:#0000FF;border:1px solid #0000FF;" title="Ветераны"></span>', '<span class="ranks-circle" style="background-color:#78777D;border:1px solid #78777D;" title="Суперветераны"></span>', '<span class="ranks-circle" style="background-color:#4BD667;border:1px solid #4BD667;" title="U13"></span>', '<span class="ranks-circle" style="background-color:#12941B;border:1px solid #12941B;" title="Юниоры"></span>', '<span class="ranks-circle" style="background-color:#FF91D3;border:1px solid #FF91D3;" title="Женщины"></span>'), '', $htmlAll);
      $htmlAll = str_replace(array('<span class="ranks-badge"></span>'), '', $htmlAll);
      $players = array();
      $html = $htmlAll;
      $count=1;
      while (strpos($html, $player.'</a></td><td class="ma_name_sep">-')<>0) {
 
        //На первой позиции игрок
       
       
           $start = strpos($html, $player.'</a></td><td class="ma_name_sep">-'); //первый
           $len = mb_strlen($player.'</a></td><td class="ma_name_sep">-'); 
       
           $html2 = substr($html,$start+$len);
       
           $endName = strpos($html2, '</a></td><td class="ma_result_b"');
           $endName1 = strpos($html2,'ma_name2">Отдых</td>');
           $lenEndGame = mb_strlen('ma_name2">Отдых</td>');
          
           
           if  ((($endName >  $endName1) and ($endName1<>0)) or ($endName==0)) { 
               //отсеивание "Отдыха"
             
             
               $html = substr($html,$endName1+$lenEndGame);
           } else {
               //парсинг Имён и Фамилий
             
               $len = mb_strlen($player.'</a></td><td class="ma_name_sep">-');          //длина
               $end = $start + $len;                  //последний
           
               $startName = $start+$len;
             
           
               $endtech = strpos($html2,'title="">');
               $endtech = $endtech + mb_strlen('title="">');
           
       
            $html2 = substr($html2,$endtech);
       
            $endName = strpos($html2, '</a></td><td class="ma_result_b"');
           $lenName = $endName;
       
           $name = substr($html2,0,$lenName);
           $nameArray = explode(' ',$name);
          
           $html = substr($html,$start+$len);
             
       
           // старт парсинга счёта
           $startScore = strpos($html, 'title="Завершён">');
           $startScore = $startScore + mb_strlen('title="Завершён">');
           
           
           $htmlForParsCity = substr($html,$startScore-150);
       
           $html = substr($html,$startScore+8);  //??? откуда 8?? возможно разобраться
           
           $endScore = strpos($html,'</td><td');
           $score = substr($html,0 , $endScore);
           $scoreArray = explode(' : ',$score);
           //  конец парсинга счёта
       
           
       
           if ($scoreArray[1]<$scoreArray[0])  $scoreBackground = 'background:#d0f0c0;'; 
           if ($scoreArray[0]<$scoreArray[1]) $scoreBackground = 'background:#ff9090;';
           if ($scoreArray[1]==$scoreArray[0]) $scoreBackground = 'background:white;';
          
           $id=Players::where('name_player', $nameArray[0])->where('family_player', $nameArray[1])->where('plaer_from_id',$userId)->first();
             if ($id == null) {
               $playerBackground = 'background:#ff9090';
               $checked = ' checked';
               $idPlayer ='';
               //парсинг Города
               $linkCitystart = strpos($htmlForParsCity,'/user/id/');
               $linkCityLen = mb_strlen('/user/id/');
               $linkCityEnd = strpos($htmlForParsCity,'" title="">');
          
               $htmlForLink = substr($htmlForParsCity, $linkCitystart+$linkCityLen);
               $linkCity = stristr($htmlForLink, '/', true);
               $htmlCity = file_get_contents('https://th.sportscorpion.com/rus/user/id/'.$linkCity);
               $htmlCity = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $htmlCity);
       
               $htmlCityStart = strpos($htmlCity,'<th>Город</th>');
               $htmlCityLen = mb_strlen('<th>Город</th>');
               $htmlCity = substr($htmlCity, $htmlCityStart+$htmlCityLen+9);
       
               $city = stristr($htmlCity, '</td>', true);
              
       //парсинг Города
             }
             else {
               $playerBackground = 'background:#d0f0c0;';
               $idPlayer = $id->id;
               $checked = '';
               $city = $id->city;
             }

          
            array_push($players, ['name' => $nameArray[0],'family' => $nameArray[1],'idPlayer'=> $idPlayer,
            'playerBackground' => $playerBackground,
            'city' => $city,
            'scoreBackground' => $scoreBackground,
            'scoreArray0' => $scoreArray[0],
            'scoreArray1' => $scoreArray[1],
            'checked' => $checked
            ]);
           
           }
          }
          $htmlLeft = $htmlAll;
          while (strpos($htmlLeft, 'title="">'.$player.'</a></td><td class="ma_result_b"')<>0) {
           
           //на второй позиции
           
           //имя
          $start = strpos($htmlLeft,'title="">'.$player.'</a></td><td class="ma_result_b"' );
       
        
          $start = $start-(mb_strlen('<a href="/rus/user/id/8334/" title=""')*5);
       
          
          $htmlLeft = substr($htmlLeft, $start);
       
          
       
          $start = strpos($htmlLeft, 'title="">');
          $start = $start + mb_strlen('title="">');
       
          $htmlforParsCity = $htmlLeft;
          $htmlLeft = substr($htmlLeft, $start);
       
          $end = strpos($htmlLeft,'</a></td>');
          $name = substr($htmlLeft, 0, $end);
           
       
       
          $nameArray = explode(' ',$name);
       
       
          $end = strpos($htmlLeft,'title="">'.$player.'</a></td>');
          $len = mb_strlen('title="">'.$player.'</a></td>');
          $end = $end + $len;
       
       
          //имя
             $htmlLeft = substr($htmlLeft, $end);
          
          $start = strpos($htmlLeft,'title="Завершён">');
         
          $start = $start + mb_strlen('title="Завершён">');
            $end = strpos($htmlLeft,'</td><td class="but"><a');
            
          
           $end = $end-$start;
            $score = substr($htmlLeft, $start+8, $end-8 ); //загадочная восьмёрка и тут
            $scoreArray = explode(' : ',$score);
       
       
            if ($scoreArray[1]>$scoreArray[0])  $scoreBackground = 'background:#d0f0c0;'; 
            if ($scoreArray[0]>$scoreArray[1]) $scoreBackground = 'background:#ff9090;';
            if ($scoreArray[1]==$scoreArray[0]) $scoreBackground = 'background:white;';
       
            $id=Players::where('name_player', $nameArray[0])->where('family_player', $nameArray[1])->where('plaer_from_id',$userId)->first();
            if ($id == null) {
              $playerBackground = 'background:#ff9090';
              $checked = 'checked';
              $idPlayer ='';
               //парсинг Города
               $linkCitystart = strpos($htmlforParsCity,'/rus/user/id/');
               $linkCityLen = mb_strlen('/rus/user/id/');
               $linkCityEnd = strpos($htmlforParsCity,'" title="">');
              
               $htmlForLink = substr($htmlforParsCity, $linkCitystart+$linkCityLen);
               $linkCity = stristr($htmlForLink, '/', true);
           
               $htmlCity = file_get_contents('https://th.sportscorpion.com/rus/user/id/'.$linkCity);
               $htmlCity = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $htmlCity);
         
               $htmlCityStart = strpos($htmlCity,'<th>Город</th>');
               $htmlCityLen = mb_strlen('<th>Город</th>');
               $htmlCity = substr($htmlCity, $htmlCityStart+$htmlCityLen+9);
         
               $city = stristr($htmlCity, '</td>', true);
              //Парсинг Города
            }
            else {
              $playerBackground = 'background:#d0f0c0;';
              $idPlayer = $id->id;
              $checked = '';
              $city = $id->city;
         
            }
            array_push($players, ['name' => $nameArray[0],'family' => $nameArray[1],'idPlayer'=> $idPlayer,
            'playerBackground' => $playerBackground,
            'city' => $city,
            'scoreBackground' => $scoreBackground,
            'scoreArray0' => $scoreArray[1],
            'scoreArray1' => $scoreArray[0],
            'checked' => $checked
            ]);
           
            }
            $count = count($players);
      return view('parser.parserView', compact('tour','players','count'));
  } 
  
  else
    {
     // Парсинг если игра ПЛЭЙ-ОФФ
    
      $userId = Auth::id(); 

      $htmlAll = file_get_contents($link);
     
   
   $htmlAll = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    ',), '', $htmlAll);
   $htmlAll = str_replace(array('<span class="ranks-circle" style="background-color:#0000FF;border:1px solid #0000FF;" title="Ветераны"></span>', '<span class="ranks-circle" style="background-color:#78777D;border:1px solid #78777D;" title="Суперветераны"></span>', '<span class="ranks-circle" style="background-color:#4BD667;border:1px solid #4BD667;" title="U13"></span>', '<span class="ranks-circle" style="background-color:#12941B;border:1px solid #12941B;" title="Юниоры"></span>', '<span class="ranks-circle" style="background-color:#FF91D3;border:1px solid #FF91D3;" title="Женщины"></span>'), '', $htmlAll);
   $htmlAll = str_replace(array('<span class="ranks-badge"></span>'), '', $htmlAll);
    
      $players = array();
      while ((strpos($htmlAll, $player.'</a></td><td class="ma_name_sep">-')<>0) or
      (strpos($htmlAll, 'title="">'.$player.'</a></td><td class="ma_result_xb')<>0))  {





           if (( ((strpos($htmlAll, $player.'</a></td><td class="ma_name_sep">-')) <
           (strpos($htmlAll, 'title="">'.$player.'</a></td><td class="ma_result_xb'))) 
           and (strpos($htmlAll, $player.'</a></td><td class="ma_name_sep">-')<>0)) or
           (strpos($htmlAll, 'title="">'.$player.'</a></td><td class="ma_result_xb')==0))
          
           {
            
        
         $html = $htmlAll;
         $start = strpos($html, $player.'</a></td><td class="ma_name_sep">-');
         $a = $start+strlen($player.'</a></td><td class="ma_name_sep">-');

         $html = substr($html, $a);
        
         $end = strpos($html, '</tr><tr class="series-container">');

         $html = substr($html, 0, $end+strlen('</tr><tr class="series-container">'));


            $start = strpos($html, 'title="">');
            $start = $start + strlen('title="">');
         
            $htmlforParsCity = $html;
            $html = substr($html, $start);
         
            $end = strpos($html,'</a></td>');
            $name = substr($html, 0, $end);      
         
            $nameArray = explode(' ',$name);
              //ПОРЯДОК??

            $id=Players::where('name_player', $nameArray[0])->where('family_player', $nameArray[1])->where('plaer_from_id',$userId)->first();
            if ($id == null) {
              $playerBackground = 'background:#ff9090';
              $checked = ' checked';
              $idPlayer ='';
              //парсинг Города
              $linkCitystart = strpos($htmlforParsCity,'/user/id/');
              $linkCityLen = mb_strlen('/user/id/');
              $linkCityEnd = strpos($htmlforParsCity,'" title="">');
         
              $htmlForLink = substr($htmlforParsCity, $linkCitystart+$linkCityLen);
              $linkCity = stristr($htmlForLink, '/', true);
              $htmlCity = file_get_contents('https://th.sportscorpion.com/rus/user/id/'.$linkCity);
              $htmlCity = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $htmlCity);
      
              $htmlCityStart = strpos($htmlCity,'<th>Город</th>');
              $htmlCityLen = mb_strlen('<th>Город</th>');
              $htmlCity = substr($htmlCity, $htmlCityStart+$htmlCityLen+9);
      
              $city = stristr($htmlCity, '</td>', true);
             
      //парсинг Города  
      
      
            }
            else {
              $playerBackground = 'background:#d0f0c0;';
              $idPlayer = $id->id;
              $checked = '';
              $city = $id->city;
             
            }



           

         $html= substr($html,strpos($html,'</a></td>'));
      
           while (strpos($html, 'title="Информация"></a>')<>0) {
           

             $st = strpos($html, 'title="Информация"></a>');

             $html = substr($html, $st+strlen('title="Информация"></a>'));

             $en = strpos($html,'<div class=');
             $en1 = strpos($html,'</td><td class="ma_result');
             $ot='';
             $type = 'p';
             if ((($en1 < $en) and ($en1<>0)) or $en==0)             
             $en = $en1;
             else {
             $ot='(OT)';
             $type = 'o';
             }
             $score = substr($html, 0, $en);
             $score = str_replace(array("<span>", "</span>"), '', $score);
          
             $scoreArray = explode(':', $score);            
      
             
             if ($scoreArray[1]<$scoreArray[0])  $scoreBackground = 'background:#d0f0c0;'; 
             if ($scoreArray[0]<$scoreArray[1]) $scoreBackground = 'background:#ff9090;';
        //если игрока нет в базе, а игр несколько в текущем турнире
           if (((count($players)) <> 0) and ( $checked <> ''))  {
                if (($nameArray[1] == $players[count($players)-1]['family']) and 
                ($nameArray[0] == $players[count($players)-1]['name'])) {
               $playerBackground = 'background:yellow;';
               $checked = '';
               $status = 'notNew';
                }
           }
       //
             array_push($players, ['name' => $nameArray[0],'family' => $nameArray[1],'idPlayer'=> $idPlayer,
             'playerBackground' => $playerBackground,
             'city' => $city,
             'scoreBackground' => $scoreBackground,
             'scoreArray0' => $scoreArray[0],
             'scoreArray1' => $scoreArray[1],
             'checked' => $checked,
             'type' => $type,
             'ot' => $ot,
             'status' => $status
             ]); 


           }
          

              $htmlAll = substr($htmlAll, $a);


        }
     
         else 
         {
           $html = $htmlAll; 
         
         
          $start = strpos($html, 'title="">'.$player.'</a></td><td class="ma_result_xb');
           $a = $start+strlen('title="">'.$player.'</a></td><td class="ma_result_xb');
           $htmlAll1 = substr($htmlAll, $a-330);

            $end = strpos($htmlAll1, '<td class="ma_result_xb">');
           
           
            $htmlAll1 = substr($htmlAll1, 0, $end);
             
        
           $startName = strpos($htmlAll1, 'title="">');
             
           $htmlAll1 = substr($htmlAll1, $startName+strlen('title="">'));
          

           $endName = strpos($htmlAll1, '</a></td><td class=');
           $name = substr($htmlAll1, 0, $endName);
           $nameArray = explode(' ',$name);

           
           $id=Players::where('name_player', $nameArray[0])->where('family_player', $nameArray[1])->where('plaer_from_id',$userId)->first();
           if ($id == null) {
             $playerBackground = 'background:#ff9090';
             $checked = 'checked';
             $idPlayer ='';
              //парсинг Города
              $linkCitystart = strpos($htmlAll1,'/rus/user/id/');
              $linkCityLen = mb_strlen('/rus/user/id/');
              $linkCityEnd = strpos($htmlAll1,'" title="">');
             
              $htmlForLink = substr($htmlAll1, $linkCitystart+$linkCityLen);
              $linkCity = stristr($htmlForLink, '/', true);
          
              $htmlCity = file_get_contents('https://th.sportscorpion.com/rus/user/id/'.$linkCity);
              $htmlCity = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $htmlCity);
        
              $htmlCityStart = strpos($htmlCity,'<th>Город</th>');
              $htmlCityLen = mb_strlen('<th>Город</th>');
              $htmlCity = substr($htmlCity, $htmlCityStart+$htmlCityLen+9);
        
              $city = stristr($htmlCity, '</td>', true);
             //Парсинг Города
             
           
           }
           else {
             $playerBackground = 'background:#d0f0c0;';
             $idPlayer = $id->id;
             $checked = '';
             $city = $id->city;
         
           }


          


           while (strpos($htmlAll1, 'title="Информация"></a>')<>0) {
           

             $st = strpos($htmlAll1, 'title="Информация"></a>');

             $htmlAll1 = substr($htmlAll1, $st+strlen('title="Информация"></a>'));

             $en = strpos($htmlAll1,'<div class=');
             $en1 = strpos($htmlAll1,'</td><td class="ma_result');
             $ot='';
             $type = 'p';
             if ((($en1 < $en) and ($en1<>0)) or $en==0)             
             $en = $en1;
             else {
             $ot='(OT)';
             $type = 'o';
             }
             $score = substr($htmlAll1, 0, $en);
             $score = str_replace(array("<span>", "</span>"), '', $score);

             $scoreArray = explode(':', $score);
           
             if ($scoreArray[0]<$scoreArray[1])  $scoreBackground = 'background:#d0f0c0;'; 
             if ($scoreArray[1]<$scoreArray[0]) $scoreBackground = 'background:#ff9090;';
           
           
               //если игрока нет в базе, а игр несколько в текущем турнире
           if (((count($players)) <> 0) and ( $checked <> '')) {
             if (($nameArray[1] == $players[count($players)-1]['family']) 
             and ($nameArray[0] == $players[count($players)-1]['name'])) {
               $playerBackground = 'background:yellow;';
               $checked = '';
               $status = 'notNew';
             }
           }
           //
              

             array_push($players, ['name' => $nameArray[0],'family' => $nameArray[1],'idPlayer'=> $idPlayer,
             'playerBackground' => $playerBackground,
             'city' => $city,
             'scoreBackground' => $scoreBackground,
             'scoreArray0' => $scoreArray[1],
             'scoreArray1' => $scoreArray[0],
             'checked' => $checked,
             'type' => $type,
             'ot' => $ot,
             'status' => $status
             ]); 


           } 

           $htmlAll = substr($htmlAll, $a);
        } 


       }
         
         
         $count = count($players);
         return view('parser.parserPO', compact('tour','players','count'));

    }


}
  public function parserPo(){
          
  }
    
}


