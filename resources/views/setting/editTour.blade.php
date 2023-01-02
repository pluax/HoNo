@extends('sections.head')

@section('content')
<h1>Добавить новый турнир </h1>

<form style='display: table;' method="post" action=/tour/update> 
    @csrf
<div class=form-group style='width:600px; margin:auto;'>
<label >Название турнира</label>
<input  name=name class="form-control" value='{{ $tour->name_tour }}' required>

<label >Дата</label>
<input name=date type=date class="form-control" value='{{ $tour->date }}' required>


<label >Ссылка</label>
<input name=link class="form-control" value='{{ $tour->link }}' required>


<label >Город</label>
<input name=city class="form-control" value='{{ $tour->city_tour }}' required>

<button type="submit" class='btn btn-primary' name=id value='{{ $tour->id }}'>Обновить данные</button>
</div>
</form>
@endsection