@extends('sections.head')

@section('content')

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
  <form action='/insert/parser/playoff' method=post>
     @csrf
 <table class="table table-bordered menu table-striped">
 <tr>
      <th scope="col">№</th>
      <th scope="col">Новый</th>
      <th scope="col">Имя</th>
      <th scope="col">Фамилия</th>
      <th scope="col">Город</th>
      <th scope="col">Счёт</th>
      <th scope="col">Добавить?</th>

 </tr>

@foreach ($players as $player)

<tr>
      <td>{{ $loop->iteration }}</td>
      <td><input class="form-check-input" {{ $player['checked'] }} type=checkbox name='CheckPlayer{{ $loop->iteration }}' value=yes> 
      <input name='status{{ $loop->iteration }}' value="{{ $player['status'] }}" hidden>
      <input class=form-control name='idPlayer{{ $loop->iteration }}' hidden value="{{ $player['idPlayer']  }} " /> </td>
          <td><input class=form-control name='name{{ $loop->iteration }}' style='{{ $player["playerBackground"] }}' value="{{  $player['name']  }}"> </td>
          <td><input class=form-control name='fam{{ $loop->iteration }}' style='{{ $player["playerBackground"] }}' value="{{  $player['family']  }}"> 
          </td>
          <td><input class=form-control name='city{{ $loop->iteration }}' style='{{ $player["playerBackground"] }}' value="{{  $player['city']  }}">
          </td>
          <td style='display:flex;'><input class=form-control name='user_score{{ $loop->iteration }}' style='width: 50px; {{ $player["scoreBackground"] }}' value="{{ $player['scoreArray0'] }}">:
          <input class=form-control name='player_score{{ $loop->iteration }}' style='width: 50px; {{ $player["scoreBackground"] }}' value="{{ $player['scoreArray1'] }}">{{ $player['ot']   }}
          <input name='type{{ $loop->iteration }}' value='{{ $player["type"] }}' hidden>
          </td>
          <td><input type=checkbox checked name='insert{{ $loop->iteration }}' value=yes></td>
           </tr>

@endforeach
</table>
<button type="submit" class='btn btn-primary'>Записать результаты</button>
<input style='display:none;' name='tourid' value='{{ $tour->id }}' >
<input style='display:none;' name=count value='{{ $count }}'>

</form>
@endsection