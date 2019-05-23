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
    @foreach($data as $billCollection)
        <tr>
            <td>{{ $billCollection->code }}</td>
            <td>{{ $billCollection->name }}</td>
            <td>{{ $billCollection->phone }}</td>
            <td>{{ $billCollection->no_of_months }}</td>
            <td>{{ $billCollection->created_at }}</td>
            <td>{{ $billCollection->total }}</td>
            <td>{{ $billCollection->discount }}</td>
            <td>{{ $billCollection->total - $billCollection->discount}} </td>
        </tr>
    @endforeach
    </tbody>
</table>