<style>
    tr:nth-child(even) {
        background-color: #F3F3F3;
    }
</style>
<table style="width: 100%">
    <thead style="background-color: #2D4154; color: white; text-align: center;">
    <tr>
        <th>Date</th>
        <th>Code</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Complain Status</th>
    </tr>
    </thead>
    <tbody class="table-bordered" style="text-align: center">
    @foreach($data as $complains)
        <tr>
            <td>{{ $complains->date }}</td>
            <td>{{ $complains->code }}</td>
            <td>{{ $complains->customer_name }}</td>
            <td>{{ $complains->phone }}</td>
            <td>{{ $complains->status_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>