@extends('verified.layout')

@section('icon', 'fa-calendar-week')
@section('title', 'Players Weekly')
@section('subtitle', 'Players who joined this week')

@section('content')
    @include('_widgets.players.widget_players')
@endsection
