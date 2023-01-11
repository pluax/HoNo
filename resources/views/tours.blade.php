
@extends('sections.head')

@section('content')

    <h1>Турниры</h1>
<form style='display:flex;' method='post' action='/tours'>
@csrf
 <div class=col-md-10>
<input name=find class=form-control value='{{ isset($find) ? $find : "" }}'>
</div>
<div class=col-md-2>
<input type=submit value='Поиск' class="btn btn-primary">
<a href="/insert/onetour">
<div title='Добавить турнир' style='float: right;
    margin-top: 5px;' class=plus>
</div>
</a>
</div>
</form>
    <table class="table table-bordered menu table-striped" style='margin-top: 20px;'>
  <thead>
    <tr>
    	<th scope="col">#</th>
      <th scope="col">Название турнира</th>
      <th scope="col">Дата</th>
      <th scope="col">Город</th>
      <th scope="col">Ссылка</th>   
    </tr>
  </thead>
  <tbody>
  	@foreach ($tours as $tour)
<tr>
 <td scope="row">
 {{ ($loop->count)-($loop->index) }}
</td>
<td>
<a href='/tour/{{ $tour->id }}'>	{{ $tour->name_tour }} </a>
	</td>
	<td>
	{{ $tour->date }}
	</td>
  <td>
	{{ $tour->city_tour }}
	</td>
	<td>
<a title='Официальная страница' href='{{ $tour->link }}' target="_blank">	<div class=link></div> </a>
	</td>
</tr>
@endforeach
</table>

            </div>  
@endsection

