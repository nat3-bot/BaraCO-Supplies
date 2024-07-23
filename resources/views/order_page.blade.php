<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order List') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row" id="order-list">

        </div>
    </div>

    <script>
        $(document).ready(function(){
            loadOrder();

            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadOrder(){
                $.ajax({
                    url:"{{route('order.load')}}",
                    method: "GET",
                    success: function(response){
                        $('#order-list').html(response.html);
                    },
                    error: function (response){
                        console.log(response);
                    }
                });
            }

        });
    </script>
</x-app-layout>