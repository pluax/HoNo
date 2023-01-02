@extends('sections.head')

@section('content')

 <h1>Турнир: <a target=_blank href='{{ $tour->link }}'>{{ $tour->name_tour }} ({{ $tour->city_tour }})</a></h1>

<table class="table table-bordered menu">
 <thead>
    <tr>
      <th scope="col">№</th>
      <th scope="col">Счёт</th>
      <th scope="col">Тип</th>
      <th scope="col">Соперник</th>
      <th scope="col">Сохранить</th>
      <th scope="col">Удалить</th>
    </tr>
  </thead>
    <tbody>
    @foreach($games as $game)
    <tr>   
    <td>
     {{ $loop->iteration}}
    </td>
    <form method="post" action="/update/onegame/{{ $tour->id }}">
    <td style='display:flex; {{ $game->background }}' >
    <input name=gf class=form-control style='width:50px;' value='{{ $game->goal_for }}' required> :
       <input style='width:50px;' class=form-control name=ga value='{{ $game->goal_away }}' required>
    </td>
    <td>
    <select name=type class="form-select" aria-label="">
    <option value="r" <?php if (($game->type)=='r')  echo 'selected'; ?>>Турнир</option>
    <option value="p" <?php if (($game->type)=='p')  echo 'selected'; ?>>Плэй-Офф</option>
    <option value="o" <?php if (($game->type)=='o')  echo 'selected'; ?>>Плэй-Офф (ОТ)</option>
    </select>
    </td>
    <td>
    <a href='/player/{{ $game->id }}'>   {{ $game->family_player }} {{ $game->name_player }}  </a>
    </td>
    <td>
    
    @csrf
    <input title="Сохранить" type="submit" class="save" value="{{ $game->gameId }}" name="id">
    </form>
    </td> 
    <td>
      <form method="post" action="/delete/onegame/{{ $tour->id }}">
      @csrf
    <input title="Удалить матч" type="submit" class="delete" value="{{ $game->gameId }}" name="id">
      </form>
    </td>   
    </tr>
    @endforeach
    </tbody>
</table>
 @endsection