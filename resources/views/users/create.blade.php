@extends('layouts.app')

@section('title', 'Novo Usuário')

@section('content')
    <h1>Novo Usuário</h1>

    @include('includes.validation-form')

    <form action=" {{ route('users.store')}}" method="post">
        @include('users._partials.form')
    </form>
@endsection

