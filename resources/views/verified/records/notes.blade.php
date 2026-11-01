@extends('verified.layout')

@section('icon', 'fa-sticky-note')
@section('title', 'Notes')
@section('subtitle', 'Staff notes attached to players')

@section('content')
    @include('_widgets.records.widget_notes')
@endsection
