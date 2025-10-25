@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
@endsection
@section('content')
    <!-- ===============>> account start here <<================= -->
    <section class="account padding-top padding-bottom">
        <div class="container">
            <div class="account__wrapper">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="account__content account__content--style1">
                            <!-- account tittle -->
                            <div class="account__header">
                                <h2>Create Your MamaCare Account</h2>
                                <p>Register to track your baby’s kicks, plan your meals, and share your beautiful journey with your husband — every heartbeat, every moment, together</p>
                            </div>
                            <!-- account form -->
                            <form action="{{ route('register') }}" method="POST" class="account__form needs-validation" novalidate>
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div>
                                            <label for="name" class="form-label">Name <span class="text-danger fs-6">*</span> </label>
                                            <input class="form-control" name="name"  value="{{ old('name') }}" type="text" id="name" placeholder="Ex. Jhon">

                                            @error('name')
                                                <span class="my-2 text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <label for="account-email" class="form-label">Email <span class="text-danger fs-6">*</span></label>
                                            <input type="email" class="form-control" id="account-email" placeholder="Enter your email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="my-2 text-danger "> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-pass">
                                            <label for="newPassword" class="form-label">Password <span class="text-danger fs-6">*</span></label>
                                            <input type="password" name="password" class="form-control" id="newPassword"  placeholder="Password" autocomplete="current-password" required>

                                            <button type="button" id="btnToggle" class="form-pass__toggle"><i id="NewTogglePassword" class="fa fa-eye"></i></button>
                                        </div>
                                        <span class="my-2 text-danger " id="newPasswordError"></span>
                                        @error('password')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="form-pass">
                                            <label for="account-cpass" class="form-label">Confirm Password <span class="text-danger fs-6">*</span></label>
                                            <input type="password" name="password_confirmation" class="form-control" id="confirmPassword" placeholder="Re-type password" required>

                                            <button type="button" id="btnCToggle" class="form-pass__toggle"><i  id="eyeIcon2" class="fa fa-eye"></i></button>
                                        </div>
                                        <span class="text-danger" id="confirmPasswordError"> </span>
                                    </div>

                                </div>

                                <button type="submit" class="trk-btn trk-btn--border trk-btn--primary d-block mt-4">Sign Up</button>

                            </form>
                            <div class="account__switch">
                                <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===============>> account end here <<================= -->


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
