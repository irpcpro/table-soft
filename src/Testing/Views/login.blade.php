<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-grid.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('assets/bootstrap/css/bootstrap-reboot.min.css')}}" />
    </head>
    <body>

        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-4">
                    <h1>Bugloos login</h1>
                    <form action="{{route('tableview.login.post')}}" method="post">
                        <div class="form-control">
                            <label for="email">Email:</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{old('email')}}">
                            @error('email')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror

                            <label for="password">password:</label>
                            <input id="password" name="password" type="password" class="form-control" value="">
                            @error('password')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror

                            @error('login-error')
                                <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror


                            <button class="btn btn-success mt-2" type="submit">Login</button>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        </div>

    </body>
</html>
