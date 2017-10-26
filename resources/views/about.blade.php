@extends('layouts.app')
@section('title', 'Login')

@section('hero')
    <img src="{{ Storage::url('app/usa.png') }}" class="border-radius-circle">
@endsection

@section('content')
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <div class="content">
                <h3><b>It's educational project</b> that allows everyone to share information about world interesting places with advantage of <span class="has-text-info">Google Map Api.</span></h3>

                <h5><b>Client section</b></h5>
                <ul>
                    <li>Everyone can create interesting places.</li>
                    <li>Everyone can find places stored in database.</li>
                    <li>Available filters:
                        <ul>
                            <li><em>address with search radius</em></li>
                            <li><em>country</em></li>
                            <li><em>name</em></li>
                            <li><em>author</em></li>
                            <li><em>favorite (only for registered users)</em></li>
                            <li><em>popular</em></li>
                        </ul>
                    </li>
                </ul>

                <h5><b>Admin section</b></h5>
                <ul>
                    <li>Admin can update and delete any place.</li>
                    <li>Authenticated user can update and delete own place.</li>
                    <li>Authenticated user can add places to own favorite list.</li>
                </ul>

                <h5><b>Roles</b></h5>
                <ul>
                    <li><b>Guest</b>. There are no special requirements but some functionality (favorite list, editing mode) are not available.</li>
                    <li><b>Own account</b>. You should register new user and confirm your email address. You will have access to all site features.</li>
                    <li><b>Demo account</b> (username - <em>demo@example.com</em>, password - <em>secret</em>). You will have access to all site features without necessary of registration.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
