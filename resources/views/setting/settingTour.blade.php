@extends('sections.head')

@section('content')
<h1>Редактирование турниров</h1>
    <table class="table table-bordered menu table-striped" style='margin-top: 20px;'>
  <thead>
    <tr>
    	<th scope="col">#</th>
      <th scope="col">Название турнира</th>
      <th scope="col">Дата</th>
      <th scope="col">Редактировать данные</th>   
      <th scope="col">Редактировать игры</th> 
      <th scope="col">Удалить</th> 
    </tr>
  </thead>
  <tbody>
  	<?php 
  $i=0;
  	?>
  	@foreach ($tours as $tour)
<tr>
 <td scope="row">
 <?php $i++; echo $i; ?> 
</td>
<td>
<a href='/tour/{{ $tour->id }}'>	{{ $tour->name_tour }} </a>
	</td>
	<td>
	{{ $tour->date }}
	</td>
	<td>
    <form method="post" action="/edit/tour">
    @csrf
    <input title='Редактировать данные турнира' type=submit class="clipboard" value='{{ $tour->id }}' name=id>
    </form>
	</td>
    <td>
    <a href="/edit/games/{{ $tour->id }}">
    <input title='Редактировать игры' type=submit class="pencil" value='{{ $tour->id }}' name=id>
    </a>
    </td>
    <td>
    <form method="post" action="/delete/tour">
    @csrf
    <input title='Удалить турнир' type=submit class="delete" value='{{ $tour->id }}' name=id>
    </form>
    </td>
</tr>
@endforeach
</table>

@endsection