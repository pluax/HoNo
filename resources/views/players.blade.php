
@extends('sections.head')

@section('content')
<h1>Игроки </h1>
<form style='display:flex;' method='post' action='/players'>
@csrf
  <div class=col-md-10>
<input name=find class=form-control value='{{ isset($find) ? $find : "" }}'>
</div>
<div class=col-md-2>
<input type=submit value='Поиск' class="btn btn-primary">
</div>

</form>
<table class="table table-bordered menu" style='margin-top: 20px;'>
  <thead>
    <tr>
    	<th scope="col">#</th>
      <th scope="col">Имя</th>
      <th scope="col">Фамилия</th>
      <th scope="col">Город</th>   
      <th scope="col">Сохранить</th>
      <th scope="col">Удалить</th>
    </tr>
  </thead>
  <tbody>
  	@foreach ($players as $player)
<tr>
 <td scope="row">
 {{ $player->id }}
</td>
<form method="post" action="update_player">
@csrf
<td>
<input name=name class=form-control	value='{{ $player->name_player }}' required>
	</td>
	<td>
	<input name=family class=form-control	value='{{ $player->family_player }}' required>
	</td>
	<td>
  <input name=city class=form-control	value='{{ $player->city }}' required>
	</td>
  <td>
  <input type=submit class="save" value='{{ $player->id }}' name=id>
  </td>
  <td>
</form>
    <form method="post" action="/delete_player">
    @csrf
  <input type=submit class="delete" value='{{ $player->id }}' name=id>
</form>
  </td>
</tr>
@endforeach
</table>
{{ $players->links('vendor.pagination.simple-bootstrap-4') }}





<h2>Новый соперник</h2>
<form action=new_player method="post" style='display:flex;'>
@csrf
<div class=col-md-3>
<input placeholder="Фамилия" name=family class=form-control required>
</div>
<div class=col-md-3>
<input placeholder="Имя" name=name class=form-control required>
</div>
<div class=col-md-3>
<input placeholder="Город" name=city class=form-control required>
</div>
<div class=col-md-3>
<input type=submit value=Добавить class="btn btn-primary">
</div>
</form>
            </div> 
@endsection