<html>
<head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-grid.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-reboot.min.css')}}" />
    </head>
    <body>

        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-4">
                    <h1>welcome ....</h1>
                    <a href="{{route('tableSoft.managementTable')}}">
                        <button class="btn btn-dark">View table</button>
                    </a>
                    <a href="{{route('tableSoft.tableSetting')}}">
                        <button class="btn btn-primary">Table setting</button>
                    </a>
                    <form action="{{route('tableSoft.logout')}}" method="post" class="mt-2">
                        <button class="btn btn-danger">Logout</button>
                        @csrf
                    </form>
                </div>
            </div>
        </div>

    </body>
</html>
