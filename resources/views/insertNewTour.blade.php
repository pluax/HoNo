@extends('sections.head')

@section('content')
<h1>Добавить новый турнир </h1>

<form style='display: table;' method="post" action=/tour/new> 
    @csrf
<div class=form-group style='width:600px; margin:auto;'>
	<label >Название турнира</label>
<input  name=name class="form-control" required>

<label >Дата</label>
<input name=date type=date class="form-control" required>


<label >Ссылка</label>
<input name=link class="form-control" required>


<label >Город</label>
<input name=city class="form-control" required>

<label >Добавить игр:</label>
<input name=count type="number" class="form-control" >
<button type="submit" class='btn btn-primary'>Далее</button>
</div>
<input type=checkbox value=parser name=parser id=parser><label style='margin-left:10px; cursor:pointer;'  for=pars> Использовать парсер </label> 
</form>
@endsection