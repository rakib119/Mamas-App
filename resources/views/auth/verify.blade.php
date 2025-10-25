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
                                <h2>{{ __('Verify Your Email Address') }}</h2>
                                <p>{{ __('Before proceeding, please check your email for a verification link.') }}
                                    {{ __('If you did not receive the email') }},</p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <!-- account form -->
                            <form action="{{ route('verification.resend') }}" method="POST" class="account__form needs-validation" novalidate>
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div>
                                            <label for="account-email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="account-email" placeholder="Enter your email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                        </div>
                                        @error('email')
                                            <span class="my-2 text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="trk-btn trk-btn--border trk-btn--primary d-block mt-4">{{ __('click here to request another') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
