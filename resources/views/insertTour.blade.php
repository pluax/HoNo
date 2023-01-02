@extends('sections.head')

@section('content')
<h1>Добавить игры </h1>

<a href="/insert/onetour">
    <button class="btn btn-primary" type="button" style='font-size: 24px;'>Добавить новый турнир</button>
</a>

<a href="/insert/select/tour">
<button class="btn btn-primary" type="button" style='font-size: 24px;'>Добавить игры в существующий турнир</button>
</a>
@endsection