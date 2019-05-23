<style>
    tr:nth-child(even) {
        background-color: #F3F3F3;
    }
</style>
<table style="width: 100%">
    <thead style="background-color: #2D4154; color: white; text-align: center;">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th>
    </tr>
    </thead>
    <tbody class="table-bordered" style="text-align: center">
    @foreach($data as $users)
        <tr>
            <td>{{ $users->id }}</td>
            <td>{{ $users->name }}</td>
            <td>{{ $users->email }}</td>
            <td>{{ $users->roles_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>