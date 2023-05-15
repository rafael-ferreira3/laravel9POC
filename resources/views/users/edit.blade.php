@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <h1>Editar o Usuário {{ $user->name }}</h1>

    @include('includes.validation-form')

    <form action="{{ route('users.update',$user->id) }}" method="post">
        @method('PUT')
        @include('users._partials.form')

    </form>
@endsection

