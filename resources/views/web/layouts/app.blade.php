<!DOCTYPE html>
<html>

    <head>
        @include('web.includes.head')
    </head>


    <body>

        <div id="pageWrapper">
            <div class="header_top">
                @include('web.includes.header')
            </div>

            @yield('content')

            <!-- footer -->
            @include('web.includes.footer')
        </div>

        @include('web.includes.foot')

    </body>

</html>
