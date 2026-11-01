@extends('verified.layout')

@section('icon', 'fa-users')
@section('title', 'All Players')
@section('subtitle', 'Complete database of all registered players')

@section('content')
    @include('_widgets.players.widget_all_players')
@endsection
