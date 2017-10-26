@extends('layouts.app')
@section('title', 'Remind password')

@section('hero')
    <img src="{{ Storage::url('app/china.png') }}" class="border-radius-circle">
@endsection

@section('content')

    <div class="columns">
        <div class="column is-9-mobile is-offset-1-mobile is-6-tablet is-offset-3-tablet is-4-desktop is-offset-4-desktop">

            <nav class="panel">
                <p class="panel-heading">Remind password</p>
                <div class="panel-block">

                    <form class="control" action="{{route('password.email') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="field">
                            <label class="label">Email</label>
                            <p class="control">
                                <input class="input" type="text" name="email" placeholder="Email"
                                    value="{{ old('email') }}">
                                <span class="help is-danger">{{ $errors->first('email') }}</span>
                            </p>
                        </div>

                        <div class="field">
                            <p class="control">
                                <button class="button is-success">Send Password Reset Link</button>
                            </p>
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </div>
@endsection
