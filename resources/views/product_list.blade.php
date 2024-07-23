<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product View') }}
        </h2>
    </x-slot>
    <div class="container" style="align-items: flex-end;">
        <br>
        <div class="input-group mb-3">
            <span class="input-group-text" id="inputGroup-sizing-default">Search</span>
            <input type="text"  id="search" >
            
        </div>

        

        <div class="row" id="product-list">

        </div>
    </div>

    <script>
        $(document).ready(function(){
            loadProducts();

            if($('#search') == ''){
                loadProducts();
            }

            function loadProducts(){
                $.ajax({
                    url: "{{route('products.load')}}",
                    method: "GET",
                    success: function(response){
                        $('#product-list').html(response.html);
                        
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }

            

            $('#search').on('keyup', function(){
                var query = $(this).val();

                $.ajax({
                    url: "{{ route('products.search') }}",
                    type: "GET",
                    data: { searchProduct: query },
                    success: function(response){
                        $('#product-list').empty();
                        response.forEach(function(product) {
                            $('#product-list').append(`
                                <div class="col-md-4 mb-5" id="${product.name}" style="margin-top:2%;">
                                    <div class="card" style="width:350px;">
                                        <img src="storage/photos/${product.photo}" alt="" style="height: auto;width: auto;">
                                        <div class="card-body">
                                            <div class="card-title">${product.name}</div>
                                            <div class="card-text">Price: $${product.price}</div>
                                            <div class="card-text" style="margin: 1% 0 5% 0; font-style: oblique; opacity: 0.85;">
                                                ${product.stock > 50 ? 'In Stock!' : product.stock > 0 ? 'Only ' + product.stock + ' Items Left!' : 'Out of Stock!'}
                                            </div>
                                            <a href="product-page/${product.id}" type="button" class="btn btn-primary">Shop</a>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            });
        });
    </script>
</x-app-layout>