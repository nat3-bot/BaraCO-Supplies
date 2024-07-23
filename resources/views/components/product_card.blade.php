@foreach ($products as $product)
    <div class="col-md-4 mb-5"  id="{{$product->name}}" style="margin-top:2%;">
        <div class="card" style="width:350px;">
            <img src="{{asset('storage/photos/'. $product->photo)}}" alt="" style="height: auto;width: auto;">
            <div class="card-body">
                <div class="card-title">
                    {{$product->name}}
                </div>
                <div class="card-text">
                    Price: ${{$product->price}}
                </div>
                <div class="card-text" style="margin: 1% 0 5% 0; font-style: oblique; opacity: 0.85; ">
                    @if ($product->stock >50)
                        In Stock!
                    @endif
                    @if ($product->stock < 50)
                        Only {{$product->stock}} Items Left!
                    @endif
                    @if ($product->stock == 0)
                        Out of Stock!
                    @endif
                </div>
                <a href="product-page/{{$product->id}}" type="button" class="btn btn-primary">Shop</a>
            </div>
        </div>
    </div>    
@endforeach