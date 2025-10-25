@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
@endsection
@section('content')
    <section class="account padding-top padding-bottom">
        <div class="container">
            <div class="account__wrapper">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="account__content account__content--style1">
                            <!-- account tittle -->
                            <div class="account__header">
                                <h2>Reset Your Password</h2>
                                <p>Hey there! Forgot your password? No worries, just click "forgot password" and follow the steps to
                                recover it. Easy peasy lemon squeezy!</p>
                            </div>


                            <!-- account form -->
                            <form action="{{ route('password.confirm') }}" method="POST" class="account__form needs-validation" novalidate>
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div>
                                            <label for="password" class="form-label">Password</label>
                                            <input id="password" placeholder="Current Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" autofocus>
                                        </div>
                                        @error('password')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label for="password" class="form-label">Password</label>
                                            <input name="password" class="form-control" type="password" placeholder="Password">
                                        </div>
                                        @error('password')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="account__check">
                                    <div class="account__check-remember">
                                        <input type="checkbox" class="form-check-input" name="remember" value="" id="terms-check">
                                        <label for="terms-check" class="form-check-label"> Remember me </label>
                                    </div>
                                </div>
                                <button type="submit" class="trk-btn trk-btn--border trk-btn--primary d-block mt-4">{{ __('LOG IN') }}</button>
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
