@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
@endsection
@section('content')
    <section class="account padding-top padding-bottom ">
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
                            <form action="{{ route('password.update') }}" method="POST" class="account__form needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div>
                                            <label for="account-email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="account-email" placeholder="Enter your email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                        </div>
                                        @error('email')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="form-pass">
                                            <label for="newPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" id="newPassword"  placeholder="Password" autocomplete="new-password" required>

                                            <button type="button" id="btnToggle" class="form-pass__toggle"><i id="NewTogglePassword" class="fa fa-eye"></i></button>
                                        </div>
                                        <span class="my-2 text-danger " id="newPasswordError"></span>
                                        @error('password')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="form-pass">
                                            <label for="account-cpass" class="form-label  @error('password_confirmation') is-invalid @enderror">Confirm Password</label>
                                            <input type="password" name="password_confirmation" class="form-control" id="confirmPassword" placeholder="Re-type password" required>

                                            <button type="button" id="btnCToggle" class="form-pass__toggle"><i  id="eyeIcon2" class="fa fa-eye"></i></button>
                                        </div>
                                        <span class="text-danger" id="confirmPasswordError"> </span>
                                    </div>
                                </div>
                                <button type="submit" class="trk-btn trk-btn--border trk-btn--primary d-block mt-4">{{ __('Reset Password') }}</button>
                            </form>
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

            var passInput = $("#newPassword");
            if (passInput.attr('type') === 'password') {
                passInput.attr('type', 'text');
            } else {
                passInput.attr('type', 'password');
            }
        });
        $('#btnCToggle').click(function() {
            var passInput = $("#confirmPassword");
            if (passInput.attr('type') === 'password') {
                passInput.attr('type', 'text');
            } else {
                passInput.attr('type', 'password');
            }
        });
        $('#confirmPassword').keyup(function() {
            var newPassword = $("#newPassword").val();
            var confirmPassword = $(this).val();
            if (newPassword) {
                if (newPassword != confirmPassword) {
                    $('#confirmPasswordError').html("Password doesn't matched");
                } else {
                    $('#confirmPasswordError').html("");
                    $('#newPasswordError').html("");
                }
            } else {
                $('#confirmPasswordError').html("Please set a new password first");
            }
        });

        $('#newPassword').keyup(function() {
            var confirmPassword = $("#confirmPassword").val();
            var newPassword = $(this).val();
            if (confirmPassword) {
                if (newPassword != confirmPassword) {
                    $('#newPasswordError').html("password doesn't matched");
                } else {
                    $('#confirmPasswordError').html("");
                    $('#newPasswordError').html("");
                }
            }
        });
    </script>
@endsection
