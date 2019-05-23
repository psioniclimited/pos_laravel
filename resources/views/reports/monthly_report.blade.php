<style>
     h1, h3, h4, h6, span, small, p, table {
        font-family: "Montserrat", sans-serif;
     }

    span {
        font-size: 1rem;
    }

    .alignMe {
        list-style-type: none;
    }

    .alignMe span {
        display: inline-block;
        width: 50%;
        position: relative;
        padding-right: 10px; /* Ensures colon does not overlay the text */
    }

    .alignMe li span:first-child::after {
        content: ":";
        position: absolute;
        right: 10px;
    }
</style>

<body>
<div style="text-align: center">
    <h3>{{$user->company->name}}</h3>
    <small>Monthly Report for {{$currentMonthLabel}}</small>
</div>
<br><br>

<ul class="alignMe">
    <li><span>Number Of Connected Customers </span><span>{{$connectedCustomers}}</span></li>
    <li><span>Number Of Disconnected Customers </span><span>{{$disconnectedCustomers}}</span></li>
</ul>

<hr>

<ul class="alignMe">
    <li><span>Monthly Collection</span><span>BDT {{$thisMonthCollection}}</span></li>
    <li><span>Target Bill </span><span>BDT {{$targetBill}}</span></li>
    <li><span>Total Due </span><span>BDT {{$customerDue[0]->total}}</span></li>
    <li><span>Total Expense </span><span>BDT {{$totalExpense}}</span></li>
</ul>

<hr>

<h4 style="text-align: center">Collector Ranking</h4>

{{--<ul class="alignMe">--}}
{{--    <li><span style="text-decoration: underline; font-weight: bold">Collector</span><span--}}
{{--                style="text-decoration: underline; font-weight: bold">Collected</span></li>--}}
{{--    @foreach($thisMonthRanking as $collectors)--}}
{{--        <li>--}}
{{--            <span>{{$collectors->collector}}</span>--}}
{{--            @if($collectors->collected != null)--}}
{{--                <span>BDT {{$collectors->collected}}</span>--}}
{{--            @else--}}
{{--                <span>BDT 0</span>--}}
{{--            @endif--}}
{{--        </li>--}}
{{--    @endforeach--}}
{{--</ul>--}}

<div style="text-align: center">
<table style="text-align: center; margin: 0 auto">
    <thead style="background-color: #2D4154; color: white; text-align: center;">
    <tr>
        <th>Collector</th>
        <th>Collected</th>
    </tr>
    </thead>

    <tbody class="table-bordered" style="text-align: center">
    @foreach($thisMonthRanking as $collectors)
        <tr>
            <td>{{ $collectors->collector }}</td>
            @if($collectors->collected != null)
                <td>
                    {{ $collectors->collected }}
                </td>
            @else
                <td>
                    0
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div style="text-align: right">
    <small>Date Generated: {{$generatedDateLabel}}</small>
</div>
</body>