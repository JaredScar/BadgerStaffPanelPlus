@extends('verified.layout')

@section('icon', 'fa-users')
@section('title', 'Players Today')
@section('subtitle', 'Players who joined today')

@section('content')
    @include('_widgets.players.widget_players')
@endsection
