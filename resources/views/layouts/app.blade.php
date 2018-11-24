@include('partials._head')
<body>
    <div id="app">
        @include('partials._nav')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
