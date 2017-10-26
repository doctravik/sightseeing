<nav class="navbar is-light" role="navigation" aria-label="main navigation">
    <div class="container">
    <div class="navbar-brand">
        <a class="navbar-item logo" href="/">
            <img src="{{ Storage::url('/app/Logo.svg') }}" alt="Logo" width="60">
            <b>{{ config('app.name') }}</b>
        </a>

        <button class="button navbar-burger is-light" @click="toggleNav" ref="navbarBurger">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <div class="navbar-menu" ref="navbarMenu">
        <div class="navbar-start">
            <a class="navbar-item" href="{{ url('/places') }}">
                Catalog
            </a>
            <a class="navbar-item" href="{{ url('/geo-search') }}">
                Map
            </a>
            <div class="navbar-item is-hidden-touch">
                <a class="button is-info" href="{{ url('/places/create') }}">
                    <span class="icon"><i class="fa fa-thumb-tack" aria-hidden="true"></i></span>
                    <span>New sight</span>
                    </a>
            </div>

            <a class="navbar-item is-hidden-desktop" href="{{ url('/places/create') }}">Create new place</a>

            <a class="navbar-item" href="{{ url('/about') }}">
                About
            </a>
        </div>

        <div class="navbar-end">
            @guest
                <a class="navbar-item" href="{{ url('/register') }}">Register</a>
                <a class="navbar-item" href="{{ url('/login') }}">Login</a>
            @endguest

            @auth
                <div class="navbar-item has-dropdown is-hoverable">
                    <div class="navbar-link">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="navbar-dropdown is-right">
                        <a href="/places?my" class="navbar-item">My places</a>
                        <a href="/places?favorites" class="navbar-item">Favorites</a>

                        @if(! auth()->user()->confirmed)
                            <form action="{{ route('send.confirmation.token') }}" method="POST" id="ct-form">
                                {{ csrf_field() }}

                                <a class="navbar-item" onclick="event.preventDefault();
                                    document.getElementById('ct-form').submit()">Request confirmation token
                                </a>
                            </form>
                        @endif

                        <form action="{{ url('/logout') }}" method="POST" id="logout">
                            {{ csrf_field() }}

                            <a class="navbar-item" onclick="event.preventDefault();
                                document.getElementById('logout').submit()">Logout
                            </a>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
    </div>
</nav>
