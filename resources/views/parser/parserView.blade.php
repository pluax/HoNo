
@extends('sections.head')

@section('content')
<?php

use Illuminate\Http\Request;
use App\Models\Players;
use Illuminate\Support\Facades\Auth;

//ПЕРЕПИСАТЬ!! если будет время логику - в контроллер
$userId = Auth::id();  //яя



        $player = $name.' '.$family;


  //  $html = file_get_contents('https://th.sportscorpion.com/rus/tournament/stage/15798/table/'); 
//   $url = $link;
//   $htmlAll = file_get_contents($url);
  $htmlAll = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $htmlCode);
  $htmlAll = str_replace(array('<span class="ranks-circle" style="background-color:#0000FF;border:1px solid #0000FF;" title="Ветераны"></span>', '<span class="ranks-circle" style="background-color:#78777D;border:1px solid #78777D;" title="Суперветераны"></span>', '<span class="ranks-circle" style="background-color:#4BD667;border:1px solid #4BD667;" title="U13"></span>', '<span class="ranks-circle" style="background-color:#12941B;border:1px solid #12941B;" title="Юниоры"></span>', '<span class="ranks-circle" style="background-color:#FF91D3;border:1px solid #FF91D3;" title="Женщины"></span>'), '', $htmlAll);
  $htmlAll = str_replace(array('<span class="ranks-badge"></span>'), '', $htmlAll);
 ?>
        
      <?php
$html = $htmlAll;
 $i=1;
 ?>  
 <label style='text-align: center;
   font-size: 25px;
'>Добавление игр в турнир</label>
<table class="table table-bordered menu">
 <thead>
   <tr>
     <th scope="col">Название турнира</th>
     <th scope="col">Дата</th>
  <th scope="col">Город</th>
   </tr>
 </thead>
 <tbody>
   <tr>
   <td>
   {{ $tour->name_tour }}
   </td>
    <td>
   {{ $tour->date }} 
   </td>
    <td>
   {{ $tour->city_tour }} 
   </td>
</tr>
</tbody>
</table>
  <form action='/insert/parser' method=post>
     @csrf
 <table class="table table-bordered menu table-striped">
 <tr>
      <th scope="col">№</th>
      <th scope="col">Новый</th>
      <th scope="col">Имя</th>
      <th scope="col">Фамилия</th>
      <th scope="col">Счёт</th>
      <th scope="col">Добавить?</th>

 </tr>



 <?php
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
    
       // $startName = strpos($html, $player.'</a></td><td class="ma_name_sep">-');
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
      }
      else {
        $playerBackground = 'background:#d0f0c0;';
        $idPlayer = $id->id;
        $checked = '';
      }
    ?>
  <tr>
    <td><?php echo $i; ?></td>
    <td><input class="form-check-input" <?php echo $checked; ?> type=checkbox name=CheckPlayer<?php echo  $i; ?> value=yes> 
  <input class=form-control name=idPlayer<?php echo  $i; ?> hidden value=<?php if (isset($idPlayer)) echo $idPlayer; ?> > </td>
   <td><input class=form-control name=name<?php echo $i; ?> style='<?php echo $playerBackground; ?>' value="<?php echo $nameArray[0];  ?>"> </td>
   <td><input class=form-control name=fam<?php echo $i; ?> style='<?php echo $playerBackground; ?>' value="<?php echo $nameArray[1];  ?>">
   <input class=form-control name=city<?php echo $i; ?> value="-" hidden> </td>
   <td style='display:flex;'><input class=form-control name=user_score<?php echo $i; ?> style='width: 50px; <?php echo $scoreBackground; ?>' value="<?php echo $scoreArray[0];  ?>">:
   <input class=form-control name=player_score<?php echo $i; ?> style='width: 50px; <?php echo $scoreBackground; ?>' value="<?php echo $scoreArray[1];  ?>">
  
   </td>
   <td><input type=checkbox checked name=insert<?php echo $i; ?> value=yes></td>
    </tr>
    <?php  
        $i++;
    }
   }
   $htmlLeft = $htmlAll;
   while (strpos($htmlLeft, 'title="">'.$player.'</a></td><td class="ma_result_b"')<>0) {
    
    //на второй позиции
    
    //имя
   $start = strpos($htmlLeft,'title="">'.$player.'</a></td><td class="ma_result_b"' );
   $start = $start-180;

   $htmlLeft = substr($htmlLeft, $start);

   
   $start = strpos($htmlLeft, 'title="">');
   $start = $start + mb_strlen('title="">');

   $htmlLeft = substr($htmlLeft, $start);

   $end = strpos($htmlLeft,'</a></td>');
   $name = substr($htmlLeft, 0, $end);
    
   $nameArray = explode(' ',$name);


   $end = strpos($htmlLeft,'title="">'.$player.'</a></td>');
   $len = mb_strlen('title="">'.$player.'</a></td>');
   $end = $end + $len;
   //имя
      $htmlLeft = substr($htmlLeft, $end);
    //echo '<textarea>'.$htmlLeft.'</textare>';
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
      }
      else {
        $playerBackground = 'background:#d0f0c0;';
        $idPlayer = $id->id;
        $checked = '';
      }
  //   ?>
  <tr>
    <td><?php echo $i; ?></td>
    <td><input class="form-check-input" <?php echo $checked; ?> type=checkbox name=CheckPlayer<?php echo  $i; ?> value=yes> 
  <input class="form-control"  name=idPlayer<?php echo  $i; ?> hidden value=<?php if (isset($idPlayer)) echo $idPlayer; ?> >   </td>
  <td> <input class="form-control"  name=name<?php echo $i; ?> style='<?php echo $playerBackground; ?>' value="<?php echo $nameArray[0];  ?>"></td>
  <td> <input class="form-control" name=fam<?php echo $i; ?> style='<?php echo $playerBackground; ?>' value="<?php echo $nameArray[1];  ?>">
      <input class="form-control"  name=city<?php echo $i; ?> value="-" hidden>
</td>
    <td style='display:flex;'> <input class="form-control" name=user_score<?php echo $i; ?> style='width: 50px; <?php echo $scoreBackground; ?>' value="<?php echo $scoreArray[1];  ?>">:
    <input class="form-control"  name=player_score<?php echo $i; ?> style='width: 50px; <?php echo $scoreBackground; ?>'  value="<?php  echo $scoreArray[0];  ?>"></td>
    <td><input class="form-control"  name=insert<?php echo $i; ?> type=checkbox checked value=yes></td>
   </tr>
     <?php
        $i++;
     }
?>
</table>
<button type="submit" class='btn btn-primary'>Записать результаты</button>
<input style='display:none;' name='tourid' value='{{ $tour->id }}' >
<input style='display:none;' name=count value='<?php echo $i-1; ?>'>

</form>
@endsection