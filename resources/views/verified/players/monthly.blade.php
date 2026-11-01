@extends('verified.layout')

@section('icon', 'fa-calendar-alt')
@section('title', 'Players Monthly')
@section('subtitle', 'Players who joined this month')

@section('content')
    @include('_widgets.players.widget_players')
@endsection