@extends('sections.head')

@section('content')
<h1>Редактирование аккаунта </h1>
<span class='success'> {{ isset($massageUpdate) ? $massageUpdate : "" }}  </span>
<form method='post' action='/update/user' >
@csrf
<div class=container>
    <div class=row>
        <div class=col-md-6>
        <labe>Имя</label>
        <input name="name" class="form-control" value='{{ $name }}' required />
        <label>Фамилия</label>
        <input name="family" class="form-control" value='{{ $family }}' required />
        <label>email</label>
        <input name="email" class="form-control" value='{{  Auth::user()->email }}' required>
        <input class="btn btn-primary"  type=submit value='Обновить данные'> <br>
</form>
<hr>
<h3>Обновить пароль:</h3>
<span class='{{ isset($classCSS) ? $classCSS : "" }}'> {{ isset($massage) ? $massage : "" }}  </span>
<form method="post" action="/update/password">
            @csrf
            <label>Старый пароль:</label>
            <input name="oldPassword" type=password class="form-control" value='' required />
            <label>Новый пароль:</label>
            <input name="newPassword" type=password class="form-control" value='' required />
            <input class="btn btn-primary"  type=submit value='Обновить пароль' /> <br>
          
 </form>
</div>
</div>
</div>
@endsection