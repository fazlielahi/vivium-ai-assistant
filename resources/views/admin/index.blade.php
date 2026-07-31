<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Admin Dashboard</title>
</head>

<body>

<h1>Admin Dashboard</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Appointment Date</th>
            </tr>
        </thead>

        <tbody>

            @foreach($appointments as $appointment)

            <tr>

                <td>{{ $appointment->name }}</td>

                <td>{{ $appointment->phone }}</td>

                <td>{{ $appointment->service }}</td>

                <td>{{ $appointment->appointment_date }}</td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>

</html>