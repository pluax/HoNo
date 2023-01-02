
@extends('sections.head')

@section('content')

<?php
$max=$count;

for ($i=1; $i<=$max; $i++) {
?>
<style>
.overtime<?php echo $i; ?>{
display: none;
}
.nam<?php echo $i; ?>{
display: none;
}

.fam<?php echo $i; ?>{
display: none;
}
.city<?php echo $i; ?>{
display: none;
}
</style>
<?php
}
?>
<script type="text/javascript">
   jQuery(document).ready(function( ) {

<?php

for ($i=1; $i<=$max; $i++) {
   ?>	
   $('.playoff<?php echo $i; ?>, .overlabel<?php $i; ?>').click(function() {
$('.overtime<?php echo $i; ?>').toggleClass('show');

});

$('.CheckPlayer<?php echo $i; ?>, .playernew<?php $i; ?>').click(function() {
$('.nam<?php echo $i; ?>').toggleClass('show');
$('.fam<?php echo $i; ?>').toggleClass('show');
$('.city<?php echo $i; ?>').toggleClass('show');
$('.select<?php echo $i; ?>').toggleClass('hide');


});


<?php
}
   ?>
});
</script>

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

<div class=col-md-12 style='text-align: center;
   font-size: 25px;
'>Внесение игр: </div>

<?php
$max=$count;
for ($i=1; $i<=$max; $i++) {
echo	'Игра #'.$i; 
?>


<form style=' width: 100%; ' method="post" action=/insert/games> 
   @csrf
<div style='margin-bottom:30px;' class=col-md-12>
<input name="CheckPlayer<?php echo $i; ?>" class="form-check-input CheckPlayer<?php echo $i; ?>" type="checkbox" value="yes" id="CheckPlayer<?php echo $i; ?>">
   
       <label class="form-check-label playernew<?php echo $i; ?>" for="CheckPlayer<?php echo $i; ?>">
   Новый игрок (игрока нет в базе)
 </label>


   <select required class="form-control select<?php echo $i ?>" name='select<?php echo $i; ?>' id="select<?php echo $i ?>">
     <?php
     ?>
     <option selected disabled>Выберите игрока..</option>
     @foreach ($players as $player)
     <option value='{{ $player ->id  }}' >{{ $player -> family_player  }} {{ $player -> name_player  }}</option>
@endforeach
   </select>
   <div class="invalid-feedback">
      Пожалуйста, выберите игрока
    </div>
   <input  name=name<?php echo $i; ?> class="form-control nam<?php echo $i; ?>" placeholder="Имя" style='width: 50%;'>

<input name=fam<?php echo $i; ?> class="form-control fam<?php echo $i; ?>" placeholder="Фамилия" style='width: 50%;'>
<input  name=city<?php echo $i; ?> class="form-control city<?php echo $i; ?>" placeholder="Город" style='width: 25%;'>
<div class=col-md-3><input type=number required name=user_score<?php echo $i; ?> class="form-control" placeholder="" style='width: 30%;display:inline-block;'>
<input required type=number name=player_score<?php echo $i; ?> class="form-control" placeholder="" style='width: 30%;display:inline-block;'>
</div>
<div class=col-md-3><input name='playoff<?php echo $i; ?>'  class="form-check-input playoff<?php echo $i; ?>" type="checkbox" value="ok" id="poCheck<?php echo $i; ?>">
   
       <label class="form-check-label overlabel<?php echo $i; ?>" for="poCheck<?php echo $i; ?>">
   play-off
 </label>
</div>
<div class=col-md-3><input name='ot<?php echo $i; ?>'  class="form-check-input overtime<?php echo $i; ?>" type="checkbox" value="ok" id="otCheck<?php echo $i; ?>">
   
       <label class="form-check-label overtime<?php echo $i; ?>" for="otCheck<?php echo $i; ?>">
   овертайм
 </label>
</div>


</div> 

<?php
}
?>
<button type="submit" class='btn btn-primary'>Записать результаты</button>
<input style='display:none;' name='tourid' value='<?php echo $tourId; ?>' >
<input style='display:none;' name=count value='<?php echo $count; ?>'>
</form>
@endsection