@extends('web.includes.layout-2')
@section('content')
    <style>
        .main-container
        {
            height:65vh;
            margin-top: 50px;
        }
    </style>
    <main id="main" class="main-container">
        <!-- ======= Login Page ======= -->
        <style>
            .form-group {
                margin: 15px 0;
            }
        </style>
        <section id="login" class="login">
                    <div class="row justify-content-center">
                        @if( session('success'))
                            <div class="alert alert-success" style="width: 82%;">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger" style="width: 82%;">
                                {!! implode('', $errors->all(':message <br>')) !!}
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Register</div>
                                <div class="card-body">
                                    <form name="my-form"  action="{{ route('signup-process') }}" method="POST">
                                        @csrf
                                        <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                            <input type="text" id="username"  required autocomplete="off" class="form-control" placeholder="Username" name="username"  value="{{ old('username') }}">
                                        </div>

                                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <input type="email" id="email" required autocomplete="off" class="form-control" placeholder="E-mail Address" name="email" value="{{ old('email') }}">
                                        </div>
                                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                                <input type="password" id="password" required autocomplete="off" placeholder="******" class="form-control" name="password">
                                        </div>
                                        <div class="checkbox">
                                            <label><input required type="checkbox"> Accept Term and Conditions</label>
                                        </div>
                                        <div class="form-group registerbtn">
                                            <button type="submit" class="btn btn-primary">
                                                Register
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Login</div>
                                <div class="card-body">
                                    <form name="login-form"  action="{{ route('login-process') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                                <input type="text" id="email" placeholder="E-Mail Address" class="form-control" name="email">
                                        </div>
                                        <div class="form-group">
                                                <input type="password" id="password" placeholder="Password" class="form-control" name="password">
                                        </div>
                                        <div class="login-btn">
                                            <button type="submit" class="btn btn-success">
                                                Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        </section>
    </main><!-- End #main -->
@endsection

