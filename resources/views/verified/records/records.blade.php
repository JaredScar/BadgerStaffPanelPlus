@extends('verified.layout')

@section('icon', 'fa-database')
@section('title', 'All Records')
@section('subtitle', 'View all player records in one place')

@section('content')
    @include('_widgets.records.widget_records')
@endsection
