@extends('sections.head')

@section('content')

 <h1>Турнир: <a target=_blank title='Дата: {{ $tour->date }}' href='{{ $tour->link }}'>{{ $tour->name_tour }} ({{ $tour->city_tour }} )</a></h1>
<h3>{{ $wins }}-{{ $tie }}-{{ $lose }} (<span class=avg title='{{ $avgFor  }}'>{{ $goalFor }}</span>-<span class=avg title='{{ $avgAway  }}'>{{ $goalAway }}</span>)</h3> 
<table class="table table-bordered menu table-striped">
 <thead>
    <tr>
      <th scope="col">№</th>
      <th scope="col">Счёт</th>
      <th scope="col">Тип</th>
      <th scope="col">Соперник</th>

    </tr>
  </thead>
    <tbody>
    @foreach($games as $game)
    <tr>   
    <td>
     {{ $loop->iteration}}
    </td>
    <td style='{{ $game->background }}'>
     {{ $game->goal_for }} : {{ $game->goal_away }} {{ $game->ot }}
    </td>
    <td style='{{ $game->backgroundType }}'>
     {{ $game->typeText }}
    </td>
    <td>
    <a href='/player/{{ $game->id }}'>   {{ $game->family_player }} {{ $game->name_player }}  </a>
    </td>
 
    
    </tr>
    @endforeach
    </tbody>
</table>
 @endsection