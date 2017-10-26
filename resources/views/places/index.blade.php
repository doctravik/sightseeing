@extends('layouts.app')
@section('title', 'Catalog')

@section('hero')
    <img src="{{ Storage::url('app/france.png') }}" class="border-radius-circle">
@endsection

@section('content')
    <places></places>
@endsection
