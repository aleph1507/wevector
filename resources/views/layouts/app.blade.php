@include('partials._head')
<body class="{{Request::is('login') ? 'group210' : ''}}">
    <div id="app">
        @include('partials._modal')
        @include('partials._nav')

        @include('partials._messages')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
