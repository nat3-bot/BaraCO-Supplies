<table class="table" style="margin: 1% 1% 1% 1%;">
    <thead>
        <tr>
            <th></th>
            <th scope="col">Photo</th>
            <th scope="col">Product</th>
            <th scope="col">Price</th>
            <th>Quantity</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <form action="POST" id="deleteFromCart" name="deleteFromCart">
        @foreach ($cartItems as $cartItem)
        <tr data-product-id="{{ $cartItem->product->id }}">
            <td><input type="checkbox" class="cart-item-checkbox" data-price="{{ $cartItem->product->price }}" data-quantity="{{ $cartItem->quantity }}"></td>
            <td><img src="{{ asset('storage/photos/'. $cartItem->product->photo) }}" alt="" style="height: 100px; width: 100px;"></td>
            <td>{{ $cartItem->product->name }}</td>
            <td>${{ $cartItem->product->price }}</td>
            <td>{{ $cartItem->quantity }}</td>
            <td><button type="button" class="btn btn-danger delete-item" data-product-id="{{ $cartItem->product->id }}">Delete from Cart</button></td>
        </tr>
        @endforeach
        </form>
    </tbody>
</table>

<div style="margin-top: 5%;">
    <h4>Total Price: $<span id="total-price">0.00</span></h4>
    <form id="checkout-form" method="POST" action="{{ route('paypal') }}">
        @csrf
        <input type="hidden" name="items" id="items-input">
        <input type="hidden" name="total_price" id="total-price-input">
        <button type="submit" class="btn btn-primary">Checkout</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        function calculateTotalPrice() {
            let totalPrice = 0;
            $('.cart-item-checkbox:checked').each(function() {
                let price = parseFloat($(this).data('price'));
                let quantity = parseInt($(this).data('quantity'));
                totalPrice += price * quantity;
            });
            $('#total-price').text(totalPrice.toFixed(2));
        }

        $('.cart-item-checkbox').change(function() {
            calculateTotalPrice();
        });

        $('#checkout-form').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting normally
            if(confirm("Are you Sure You're going to checkout???")==true){
            let selectedItems = [];
            $('.cart-item-checkbox:checked').each(function() {
                selectedItems.push({
                    productId: $(this).closest('tr').data('product-id'),
                    quantity: $(this).data('quantity')
                });
            });

            $('#items-input').val(JSON.stringify(selectedItems));
            $('#total-price-input').val($('#total-price').text());

            this.submit(); // Now submit the form
            }
        });

        $('.delete-item').click(function() {
            let productId = $(this).data('product-id');
            let row = $(this).closest('tr');

            $.ajax({
                url: '{{ route("cart.delete") }}', // Update this route
                method: 'DELETE',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                        calculateTotalPrice();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(error) {
                    alert('Error: ' + error.responseJSON.message);
                }
            });
        });
    });
</script>
