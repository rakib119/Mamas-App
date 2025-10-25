@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
@endsection
@section('content')
    <section class="account">
        <div class="container">
            <div class="account__wrapper" >
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="account__content account__content--style1">
                            <!-- account tittle -->
                            <div class="account__header">
                                <h2>Welcome back!</h2>
                                <p>A smart pregnancy companion for every mom.</p>
                                <p>Log in to track baby kicks, organize meals, and stay connected with your partner — because every moment of motherhood matters.</p>
                            </div>
                            <!-- account form -->
                            <form action="{{ route('login') }}"  method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div>
                                            <label for="account-email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="account-email" placeholder="Enter your email" required autofocus>
                                        </div>
                                        @error('email')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="form-pass">
                                            <label for="inputPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control showhide-pass" id="inputPassword" placeholder="Password" required>

                                            <button type="button" id="btnToggle" class="form-pass__toggle"><i  id="eyeIcon" class="fa fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="account__check">

                                    <div class="account__check-remember">
                                        <input type="checkbox" class="form-check-input" name="remember" value="" id="terms-check">
                                        <label for="terms-check" class="form-check-label">
                                        Remember me
                                        </label>
                                    </div>
                                    <div class="account__check-forgot">
                                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                                    </div>
                                </div>

                                <button type="submit" class="trk-btn trk-btn--border trk-btn--primary d-block">Sign in</button>
                            </form>
                            <div class="account__switch">
                                <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('javaScript')
    <script>
        $('#btnToggle').click(function() {

            var passInput = $("#inputPassword");
            if (passInput.attr('type') === 'password') {
                passInput.attr('type', 'text');
            } else {
                passInput.attr('type', 'password');
            }
        });
    </script>
@endsection
