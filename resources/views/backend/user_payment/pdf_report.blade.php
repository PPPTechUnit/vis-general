<!DOCTYPE html>
<html lang="en">
<head>
    <title>User payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('public/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="{{ asset('public/assets/plugins/jquery/jquery.min.js') }}"></script>
<style>
    .table td, .table th {padding: 6px; !important;}

     table, td, th {
         border: 1px solid #ddd;
         text-align: left;
     }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 30px;
    }
</style>
</head>
<body>

<div class="jumbotron text-center" style="padding-top: 20px;padding-bottom: 20px;">
    <h3>User payment</h3>
{{--    <h3>{{$na['name']}}</h3>--}}
</div>
<div class="container-fluid">
    <table class="table table-bordered" style="width:100%">
{{--        <tr><th>Winner Name</th><th>{{$na['candidates'][0]['ele_candidate']['name']}} ({{$na['candidates'][0]['party']['short_name']}})</th></tr>--}}
{{--        <tr><th>Population</th><td>{{$na['populations']}}</td></tr>--}}
{{--        <tr><th>Added Areas</th><td>{{$na['added_areas']}}</td></tr>--}}
{{--        <tr><th>Province</th><td>{{$na['province_data']['name']}}</td></tr>--}}
{{--        <tr><th>Division</th><td>{{$na['division_data']['name']}}</td></tr>--}}
{{--        <tr><th>District</th><td>{{$na['province_data']['name']}}</td></tr>--}}
{{--     --}}

    </table>
    <h3>History</h3>
    <table class="table table-bordered" style="width:100%" border="1">
        <tr>
            <th>S No</th>
            <th>Paid Amount</th>
            <th>Remaining Amount</th>
            <th>Feedback</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        @foreach($user_payment as $i=> $payment)
            <tr>
                <th>{{++$i}}</th>
                <th>{{$payment->paid_amount}}</th>
                <th>{{$payment->remaining_amount}}</th>
                <th>{{$payment->feed_back}}</th>
                <th>{{$payment->date}}</th>
                <th>{{($payment->status ==1)?"Paid":"Un Paid"}}</th>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>
