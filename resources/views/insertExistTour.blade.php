@extends('sections.head')

@section('content')

<h1>Выберите турнир, чтобы добавить в него игры:</h1>
<form style='display:flex;' method='post' action='/insert/select/tour'>
@csrf
 <div class=col-md-10>
<input name=find class=form-control value='{{ isset($find) ? $find : "" }}'>
</div>
<div class=col-md-2>
<input type=submit value='Поиск' class="btn btn-primary">
</div>

</form>
<br>

<div style='display:flex;'>
    Количество добавляемых игр:
   
</div>
    <table class="table table-bordered menu table-striped" style='margin-top: 20px;'>
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Добавить</th>
      <th scope="col">Название турнира</th>
      <th scope="col">Дата</th>
      <th scope="col">Ссылка</th>   
      <th scope="col">Парсинг</th>  
    </tr>
  </thead>
  <tbody>

  	@foreach ($tours as $tour) 
     <form method='post' action='/insert/add/game'>
<tr>
 <td scope="row">
 {{  (($count+1)-(($tours ->currentpage()-1) * $tours ->perpage() + $loop->index + 1)) }}
</td>
<td style='display:flex;'>

@csrf
<input style='width:75px;' class="form-control" name=count type=number>
<input title='Добавить игры' type=submit class="plus" value='{{  $tour->id }}' name=id>
</td>

<td>
<a href='/tour/{{ $tour->id }}'>	{{ $tour->name_tour }} </a>
	</td>
	<td>
	{{ $tour->date }}
	</td>
	<td>
<a title='Официальная страница' href='{{ $tour->link }}' target="_blank">	<div class=link></div> </a>
	</td>
  <td>

  <button class='btn btn-secondary' name=tourIdParser type=submit value='{{ $tour->id }}'>Спарсить</button>

 </td>
</tr>
</form>
@endforeach

</table>
{{ $tours->links() }}
</div>  
@endsection

