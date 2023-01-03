
@extends('sections.head')

@section('content')

 <h1>Все игры</h1>
 <form style='display:flex;' method='get' action='/'>
 <div class=col-md-10>
<input name=find class=form-control value='{{ isset($find) ? $find : "" }}'>
</div>
<div class=col-md-2>
<input type=submit value='Поиск' class="btn btn-primary">
</div>

</form>
 <table class="table table-bordered menu table-striped" style='margin-top: 10px;
'>
  <thead>
    <tr>
      <th scope="col">№</th>
      <th scope="col">Игрок</th>
      <th scope="col">Город</th>
      <th scope="col">Games</th>
      <th scope="col">W</th>
      <th scope="col">T</th>
      <th scope="col">L</th>
      <th scope="col">GF</th>
      <th scope="col">GA</th>
      <th scope="col"> + / - </th>
    </tr>
  </thead>
  <tbody>
  @foreach ($players as $player)
    <tr>
    
      <th scope="row"> {{ $loop->iteration  }} </th>
      <td>
        <a href="/player/{{ $player->player_id }}">
   {{ $player->family_player }}    {{ $player->name_player }}
        </a>
      </td>
     
  
    <td> 
      {{ $player->city }} 
    </td>

    <td>
    {{ $player->count }} 
    </td>

    <td>
    {{ $player->w }} 
    </td>


    <td>
    {{ $player->t }} 
    </td>


    <td>
    {{ $player->l }} 
    </td>

    <td>
    {{ $player->gf }} 
    </td>


    <td>
    {{ $player->ga }} 
    </td>

    <td>
    {{ $player->pm }} 
    </td>

    </tr>
    @endforeach
  </tbody>
</table>
 @endsection