<x-app-layout>
    <div class="container" style="align-items: flex-end;">
        
        <div class="row" id="cart-list">

        </div>
    </div>
</div>


    <script>
        $(document).ready(function(){
            loadCart();
            
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadCart(){
                $.ajax({
                    url:"{{route('cart.load')}}",
                    method: "GET",
                    success:function(response){
                        $('#cart-list').html(response.html);
                    },
                    error: function (response){
                        console.log(response);
                    }
                });
            }
        });


    </script>
</x-app-layout>