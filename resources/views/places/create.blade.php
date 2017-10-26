@extends('layouts.app')
@section('title', 'Create sight')

@section('hero')
    <img src="{{ Storage::url('app/netherlands.png') }}" class="border-radius-circle">
@endsection

@section('content')
    <place-create-form></place-create-form>
@endsection

