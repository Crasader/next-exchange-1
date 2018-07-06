<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<table>
    <tr>
        <th>Referral ID</th>
        <th>Ether Address</th>
        <th>Amount</th>
    </tr>
    @foreach ($results as $result)
        <tr>
            <td>{{$result->referred_by}}</td>
            <td>{{$result->ether}}</td>
            <td>{{$result->amount*10}} NEXT</td>
        </tr>
    @endforeach

</table>

</body>
</html>