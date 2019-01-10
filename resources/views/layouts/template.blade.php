<!doctype html>
<html lang="ru">
@include('chunks.head')
<body>
<div class="wrapper ">
    @include('chunks.sidebar')
    <div class="main-panel">
        @include('chunks.navbar')
        <div class="content">
            <div class="container-fluid update-container">
                @yield('content')
            </div>
        </div>
        <footer class="footer">

        </footer>
    </div>
</div>
</body>
</html>