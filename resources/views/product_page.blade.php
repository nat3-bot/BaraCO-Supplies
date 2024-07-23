<x-app-layout>
    <center>
        <div class="container">
        <div class="card mb-3" style="max-width: 800px; margin:2%;" >
            <div class="row g-0">
              <div class="col-md-4">
                <img src="{{asset('storage/photos/'. $product->photo)}}" class="img-fluid rounded-start" alt="...">
              </div>
              <div class="col-md-8" style="text-align: start;">
                <div class="card-body">
                  <h5 class="card-title" style="font-size: 40px; margin-top:5%;">{{$product->name}}</h5>
                  <h5 class="card-title" style="font-size: 20px;" >Price: <strong>${{$product->price}}</strong></h5>
                  <div class="card-text" style="margin-top:5%; font-weight:bold;">Description: </div>
                  <p class="card-text" style="font-size:15px;">{{$product->description}}</p>
                  <button type="button" class="btn btn-primary" style="margin-top:5%;" data-bs-toggle="modal" data-bs-target="#addToCartModal">
                    Add to Cart
                  </button>
                  
                  <!-- Modal -->
                  <form action="POST" id="addToCartForm" name="addToCartForm">
                    @csrf
                    <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <input type="hidden" id="suppliesId" name="suppliesId" value="{{$product->id}}">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add {{$product->name}} to Cart</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <label style="margin: 5% 0 5% 0;">How much do you want to add to your cart?</label>
                            <label style="margin: 1%;">Quantity: <input type="number" name="productQuantity" id="productQuantity"></label>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            @if(Auth::user()->role == "user")
                              <button type="submit"  class="btn btn-primary">Add to Cart </button>
                            @endif
                          </div>
                        </div>
                      </div>
                  </div>
                </form>
              </div>
            </div>
          </div> 
        </div>
    </center>


    <script>
      $(document).ready(function(){
        $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
      });
      
      $('#addToCartForm').submit(function(e){
        e.preventDefault();
        var form = new FormData(this);
        $.ajax({
          type: "POST",
          url: "{{route('add-to-cart')}}",
          data: form,
          cache: false,
          contentType: false,
          processData: false,
          success: function(data){
            console.log(data);
            $('#addToCartModal').modal('hide');
            window.location.href = "{{ route('cart') }}";
            
          },
          error: function(data){
            console.log(data);
          }

        });
      });


            
      
    </script>
    
</x-app-layout>