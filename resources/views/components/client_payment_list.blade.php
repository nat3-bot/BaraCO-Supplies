<table class="table" style="margin: 1% 1% 1% 1%;">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Client Name</th>
            <th scope="col">Email</th>
            <th scope="col">Order Id</th>
            <th scope="col">Payment ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Payment Status</th>
            
            
            
        </tr>
    </thead>
    <tbody>
        
        @foreach ($paymentLists as $paymentList)
        <tr>
            <td>{{ $paymentList->id}}</td>
            <td>{{ $paymentList->payer_name }}</td>
            <td>{{ $paymentList->payer_email }}</td>
            <td>{{ $paymentList->order_id}}</td>
            <td>{{ $paymentList->payment_id }}</td>
            <td>{{ $paymentList->amount }}</td>
            <td>
                {{$paymentList->payment_status}}
            </td>
            
        </tr>
        @endforeach
        
    </tbody>
</table>

