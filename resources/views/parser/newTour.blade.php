
@extends('sections.head')

@section('content')

    <h1>Парсер</h1>

<label style='text-align: center;
   font-size: 25px;
'>Добавление игр в турнир</label>
<table class="table table-bordered menu table-striped">
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
<h2> Ссылка для парсинга:  </h2>
<form  method=post action='/parser'>
@csrf
<div class=container>
    <div class=row>
      <h4>Ссылка на "Расписание и результаты" формата https://th.sportscorpion.com/rus/tournament/stage/[число]/matches/ </h4>
        <div class=col-md-10>
<input name=link class=form-control value='{{ isset($find) ? $find : "" }}'>
<input name=tourId hidden value='{{ $tour->id }}' > 
       </div>
       <div class=col-md-2>
       <input type=submit value='Спарсить' class="btn btn-primary" value=''>
       </div>
    </div>
    </div>
</form>
@endsection