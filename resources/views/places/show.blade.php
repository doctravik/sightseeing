@extends('layouts.app')
@section('title', 'Place profile')

@section('hero')
    <img src="{{ Storage::url('app/netherlands.png') }}" class="border-radius-circle">
@endsection

@section('content')
    <place-profile place-slug="{{ $place->slug }}"></place-profile>
@endsection
