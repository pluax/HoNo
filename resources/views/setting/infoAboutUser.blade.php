@extends('sections.head')

@section('content')
<h1>Редактирование аккаунта </h1>
<form method='post' action='/update/user' >
@csrf
<div class=container>
    <div class=row>
        <div class=col-md-6>
<labe>Имя</label>
<input name="name" class="form-control" value='{{ $name }}' required>
<label>Фамилия</label>
<input name="family" class="form-control" value='{{ $family }}' required>
<labrl>email</label>
<input name="email" class="form-control" value='{{  Auth::user()->email }}' required>
<input class="btn btn-primary"  type=submit value='Обновить данные'> <br>
</form>
<a href='/forgot-password'>Сменить пароль</a>
</div>
</div>
</div>
@endsection