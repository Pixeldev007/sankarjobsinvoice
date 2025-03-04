<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clients Data pdf</title>
    <style>
        th{
            border: 1px solid black;
            padding: 15px 0px;
        }
        td{
            border: 1px solid black;
            padding: 10px;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="width: 200%"><b>Client Name</b></th>
        <th style="width: 1000%"><b>Address</b></th>
        <th style="width: 300%"><b>Note</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <td>{{ $client->company_name }}</td>
            <td>{{ $client->address ?? 'N/A' }}</td>
            <td>{{ $client->note ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
