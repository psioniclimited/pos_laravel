<style>
    tr:nth-child(even) {
        background-color: #F3F3F3;
    }

    th, td {
        font-family: Calibri, serif;
    }

    @font-face {
        font-family: MinecrafterReg;
        src: url('https://fonts.googleapis.com/css?family=Roboto');
        font-weight: 400;
    }
</style>
<table style="width: 100%">
    <thead style="background-color: #2D4154; color: white; text-align: center;">
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Area</th>
        <th>Collectors</th>
        <th style="width: 75px">Due On</th>
        <th>Monthly Bill</th>
        <th>Due</th>
    </tr>
    </thead>
    <tbody class="table-bordered" style="text-align: center">
    @foreach($data as $customer)
        <tr>
            <td>{{ $customer->code }}</td>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ $customer->areas }}</td>
            <td>{{ $customer->users_name }}</td>
            <td>{{ $customer->due_on }}</td>
            <td>{{ $customer->monthly_bill }}</td>
            <td>{{ $customer->total_due }}</td>
        </tr>
    @endforeach
    </tbody>
</table>