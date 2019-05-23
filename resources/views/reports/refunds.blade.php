<style>
    tr:nth-child(even) {
        background-color: #F3F3F3;
    }
</style>
<table style="width: 100%">
    <thead style="background-color: #2D4154; color: white; text-align: center;">
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Bill Months</th>
        <th>Timestamp</th>
        <th>Total Bill</th>
        <th>Discount</th>
        <th>Paid</th>
    </tr>
    </thead>
    <tbody class="table-bordered" style="text-align: center">
    @foreach($data as $refund)
        <tr>
            <td>{{ $refund->code }}</td>
            <td>{{ $refund->name }}</td>
            <td>{{ $refund->phone }}</td>
            <td>{{ $refund->no_of_months }}</td>
            <td>{{ $refund->created_at }}</td>
            <td>{{ $refund->total }}</td>
            <td>{{ $refund->discount }}</td>
            <td>{{ $refund->total - $refund->discount}} </td>
        </tr>
    @endforeach
    </tbody>
</table>