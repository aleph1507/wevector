@include('partials._head')
<body>
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
