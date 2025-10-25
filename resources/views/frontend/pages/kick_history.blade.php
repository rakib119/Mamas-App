@php
    use Carbon\Carbon;
@endphp
@extends('frontend.layout.frontend')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/auth.css')}}" />
@endsection
@section('content')
    <section class="pricing padding-top-2 padding-bottom-2 bg--cover">
        <div class="container pb-5">
            <div id="chartContainer" style="height:300px; width: 100%;"></div>
        </div>
    </section>
    <section class="pricing padding-bottom-2 bg--cover">
        <div class="section-header section-header--max50">
            <h6 class="mb-10 mt-minus-5"><span>History</span></h6>
        </div>
        <div class="container pb-5">
            <div class="tm-comparison-table text-center">
                <div class="table-responsive">
                    <div id="contain">
                        <table class="table table-bordered" id="table_fixed">
                            <tbody id="table_scroll">
                                @php
                                    $check_array = array();
                                    $prev_date="";
                                @endphp
                                @foreach ($kick_data as $v)
                                    @if (!isset($check_array [$v->mst_id]))
                                        @php
                                            $i=1;
                                            $prev_kick_time="";
                                            $sessionStart = Carbon::parse($v->session_start)->timezone('Asia/Dhaka');
                                            $sessionEnd   = Carbon::parse($v->session_end)->timezone('Asia/Dhaka');

                                            $start      = $sessionStart->format('h:i A');
                                            $end        = $sessionEnd->format('h:i A');
                                            $today      = Carbon::now('Asia/Dhaka')->toDateString();
                                            $yesterday  = Carbon::yesterday('Asia/Dhaka')->toDateString();

                                            if ($sessionStart->toDateString() === $today) {
                                                $dateLabel = 'Today';
                                            } elseif ($sessionStart->toDateString() === $yesterday) {
                                                $dateLabel = 'Yesterday';
                                            } else {
                                                $dateLabel = $sessionStart->format('d M Y');
                                            }

                                            $caption = "$start--$end";
                                        @endphp
                                        <tr class="sub_header">
                                            <td>{{ $dateLabel }}</td>
                                            <td align="right" >{!! $caption !!}</td>
                                            <td>Total Kick: {{ $v->total_kick; }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        @php
                                            $gapText      = "--";
                                            $kick_time    = Carbon::parse($v->kick_time);
                                            $current_date = $sessionStart = Carbon::parse($v->session_start)->timezone('Asia/Dhaka')->format('d M Y');

                                            if (isset($prev_kick_time) && $prev_kick_time && $current_date==$prev_date ) {
                                                $gapText = $kick_time->diffForHumans($prev_kick_time, ['parts' => 2,'short' => true]);
                                            }
                                            $gapText = str_replace('before','<i class="fa-solid fa-arrow-down"></i>',$gapText);
                                            $gapText = str_replace('after','<i class="fa-solid fa-arrow-up"></i>',$gapText);
                                        @endphp
                                        <td width="33%" style="border-right: 1px solid var(--secondary-color);">{{$i++;}}</td>
                                        <td width="33%" align="right"  style="border-right: 1px solid var(--secondary-color);">{!!$gapText!!} </td>
                                        <td> {{ $kick_time->timezone('Asia/Dhaka')->format('h:i:s A'); }}</td>
                                    </tr>

                                    @php
                                        $prev_kick_time = $kick_time;
                                        $prev_date      = $current_date;
                                        $check_array [$v->mst_id] = $v->mst_id;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>

                    <div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('javaScript')


    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                // theme: "dark2", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "Day wise Kick"
                },
                axisY:{
                    includeZero: true
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    indexLabel: "{y} kick", //Shows y value on all Data Points
                    indexLabelFontColor: "#5A5757",
                    indexLabelPlacement: "outside",
                    dataPoints:  {!!json_encode($dataPoints, JSON_NUMERIC_CHECK);!!}
                }]
            });
            chart.render();

        }
    </script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
@endsection
