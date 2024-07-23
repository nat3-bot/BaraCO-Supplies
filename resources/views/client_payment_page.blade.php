<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Client Payment List') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row" id="payment-list">

        </div>
    </div>

    <script>
        $(document).ready(function(){
            loadPayment();

            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadPayment(){
                $.ajax({
                    url:"{{route('payment.load')}}",
                    method: "GET",
                    success: function(response){
                        $('#payment-list').html(response.html);
                    },
                    error: function (response){
                        console.log(response);
                    }
                });
            }

        });
    </script>
</x-app-layout>