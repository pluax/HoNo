@extends('sections.head')

@section('content')

<table class="table table-bordered menu table-striped" style='margin-top: 20px;'>
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Счёт</th>
      <th scope="col">Тип</th>
      <th scope="col">Турнир</th> 
      <th scope="col">Дата</th>
      <th scope="col">Город</th>
      <th scope="col">Ссылка</th>
    
    </tr>
  </thead>
  <h3> История игр с <span style='font-weight:bold;'> {{ $player->family_player }} {{ $player->name_player }} ({{ $player->city }}) </span> </h3>
  <h3> {{ $wins }}-{{ $tie }}-{{ $lose }} (<span class=avg title='{{ $avgFor  }}'>{{ $goalFor }}</span>-<span class=avg title='{{ $avgAway  }}'>{{ $goalAway }}</span>) </h3>
  <tbody>
  
  	@foreach ($games as $game)
    <tr>
<th scope="row">
{{ ($loop->count)-($loop->index) }}
</th>

<?php
    $color='';
    if (($game->goal_for)>($game->goal_away)) { $color='background:#d0f0c0;';  };
    if (($game->goal_for)<($game->goal_away)) { $color='background:#ff9090;';  };
    if (($game->goal_for)==($game->goal_away)) { $color='background:white;';  };


  ?>
     <td style='{{ $color }}'> 
        
	<?php
	$ot='';
    if (($game->type)=='o') {  $ot='(OT)';  } else { $ot=''; };
	?>
    {{ $game->goal_for }} : {{ $game->goal_away }} {{ $ot }}

</td>

<td style='{{ $game->background }}'>
<?php
 switch ($game->type) {
        case 'r':
          echo 'Турнир';
            break;
        case 'p':
          echo 'play-off';
            break;
        case 'o':
          echo 'play-off';
            break;
        case 't':
          echo 'Командный';
            break;
          }
          
          ?>
</td>



<td>
 <a href='/tour/{{ $game->tour_id }}'>   {{$game->name_tour  }} </a>
</td>

<td>
    {{ $game->date  }}
</td>
<td>
    {{ $game->city_tour  }}
</td>
<td>
<a title='Официальная страница'  href='{{ $game->link }}'> <div class=link></div>
        </a>
</td>
</tr>
   
 @endforeach
        
  </tbody>
</table>



@endsection