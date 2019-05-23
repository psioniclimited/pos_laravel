<style>
    tr:nth-child(even) {
        background-color: #F3F3F3;
    }
</style>
<table style="width: 100%">
    <thead style="background-color: #2D4154; color: white; text-align: center;">
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Description</th>
        <th>Paid With</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody class="table-bordered" style="text-align: center">
    @foreach($data as $expenses)
        <tr>
            <td>{{ $expenses->id }}</td>
            <td>{{ $expenses->date }}</td>
            <td>{{ $expenses->description }}</td>
            <td>{{ $expenses->paid_with_name }}</td>
            <td>{{ $expenses->amount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>