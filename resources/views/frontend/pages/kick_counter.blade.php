@extends('frontend.layout.frontend')
@section('css')
    <style>
        body {
            height: 100vh !important;
        }
    </style>

@endsection
@section('content')
    <section id="kick-container">
        <div class="kick-button" id="kickButton">
            <img id="kicking" style="display: none;" src="{{asset('assets/img/kick1.PNG')}}" alt="kick" />
            <img id="slipping" src="{{asset('assets/img/slip3.PNG')}}" alt="kick" />
        </div>
    </section>
    <section class="info-section">
        <div class="info-row">
            <div class="info-item">
                <span class="label">SESSION</span>
                <span class="value" id="firstKick">{{$session_started_at}}</span>
            </div>
            <div class="info-item">
                <span class="label">PERIOD</span>
                <span class="value" id="kickPeriod">{{$diff_hr_min}}</span>
            </div>
            <div class="info-item">
                <span class="label">LAST</span>
                <span class="value" id="lastKick">{{$session_ended_at}}</span>
            </div>
            <div class="info-item">
                <span class="label">COUNT</span>
                <span class="value" id="kickCount">{{$total_kick}}</span>
            </div>
        </div>
    </section>
@endsection
