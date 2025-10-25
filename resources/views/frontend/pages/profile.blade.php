@php
    use Carbon\Carbon;
@endphp
@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
@endsection
@section('content')
    <section class="feature feature--style1 padding-bottom padding-top-2 bg-color">
        <div class="container">
            <div class="row g-5 align-items-center justify-content-between">
                <div class="col-md-12 col-lg-12 profile_items">
                    <div class="profile_item">
                        <p>Invite Partner</p>
                    </div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="profile_item">
                            <p>Logout</p>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
