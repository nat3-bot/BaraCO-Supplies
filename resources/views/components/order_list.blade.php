<table class="table" style="margin: 1% 1% 1% 1%;">
    <thead>
        <tr>
            @if(Auth::user()->role == "admin")
            <th scope="col">Id</th>
            @endif
            <th scope="col">Client Name</th>
            <th scope="col">Order ID</th>
            <th scope="col">Product</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price</th>
            <th>Status</th>
            
        </tr>
    </thead>
    <tbody>
        
        @foreach ($orderItems as $orderItem)
        <tr>
            @if(Auth::user()->role == "admin")
            <td>{{ $orderItem->id}}</td>
            @endif
            <td>{{ $orderItem->user->name }}</td>
            <td>{{ $orderItem->order_id }}</td>
            <td>{{ $orderItem->product->name }}</td>
            <td>{{ $orderItem->quantity }}</td>
            <td>{{ $orderItem->price }}</td>
            <td>
                @if(Auth::user()->role == "admin")
                <select class="order-status-dropdown" data-order-id="{{ $orderItem->id }}">
                    <option value="pending" {{ $orderItem->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ $orderItem->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $orderItem->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @endif
                
                @if(Auth::user()->role == "user")
                {{ $orderItem->status }}
                @endif
            </td>
            
        </tr>
        @endforeach
        
    </tbody>
</table>

<script>
    $(document).ready(function(){
        $('.order-status-dropdown').change(function() {
        let orderId = $(this).data('order-id');
        let newStatus = $(this).val();
            $.ajax({
                url: "{{ route('update.order.status') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    order_id: orderId,
                    status: newStatus
                },
                success: function(response) {
                    console.log(response.message);
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });

        
    });
</script>