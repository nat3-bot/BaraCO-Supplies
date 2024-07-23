<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supplies List') }}
        </h2>
    </x-slot>

    <!-- Message -->
    @if($message = Session::get('Success'))
    <div class="alert alert-success">
        <p>{{$message}}</p>
    </div>
    @endif
    
    <!-- Add New Supplies -->
    <a href="javascript:void(0)" type="button" class="btn btn-success" onclick="add()" data-bs-toggle="modal" style="margin: 2% 0 0 2%;" data-bs-target="#product-modal">
        Add New Supplies
    </a>
    
    <!--Import Supplies-->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal" style="margin-left:2%; margin-top:2%;">
        Import Supplies
    </button>

    <!--Export Supplies-->
    <a href="{{url('exportSupplies')}}" class="btn btn-warning"  style="margin-left:2%; margin-top:2%;">
        Export Supplies
    </a>

    <!--Modal Import Supplies-->
    <form action="POST" id="importSuppliesForm" name="importSuppliesForm" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Import Supplies via CSV File</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" id="importSupplies" name="importSupplies" accept=".csv" style="margin-left:2%; margin-top:2%;" placeholder="Import Supplies"></a>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
        </div>
    </form>


    <!-- Modal Supplies -->
    <div class="modal fade" id="product-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Supplies</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
            <form action="javascript:void(0)" method="POST" id="ProductForm" name="ProductForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="productName">
                <label for="">Name</label>
                <input type="text" class="form-control" placeholder="Insert Product Name" name="name" id="name" required>
            </div>

            <div class="productDescription">
                <label>Description</label>
                <input type="text" class="form-control" placeholder="Insert Product Description" name="description" id="description" required>
            </div>

            <div class="productPrice">
                <label>Price</label>
                <input type="text" class="form-control" placeholder="Insert Product Price" name="price" id="price" required>
            </div>

            <div class="productStock">
                <label>Stock</label>
                <input type="number" class="form-control" placeholder="Insert Stocks" name="stock" id="stock" >
            </div>

            <div class="productImage">
                <label>Photo</label>
                <img id="image" src="" style="display: none;">
                <input type="file" class="form-control" placeholder="Insert Product Photo" name="photo" id="photo" required>
            </div>

        </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" id="btn-add">Save</button>
            </div>
        </form>
        </div>
    </div>
    </div>


    <!-- Table -->
    <div class="card" style="margin: 2%;">
        <div class="card-header">
          Supplies
        </div>
        <div class="card-body">
          <table class="table table-bordered" id="suppliesTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
          </table>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#suppliesTable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: "{{url('suppliesTable')}}",
                columns:[
                    {data: 'id', name: 'id'},
                    {data: 'photo', name: 'photo', render: function(data) {
                        return '<img src="' + data + '" width="150" height="150"/>';
                    }},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'price', name: 'price'},
                    {data: 'stock', name: 'stock'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'action', name: 'action', orderable:false},
                ],
                order:[[0, 'desc']]
            });
        });

        function editf(id){
            $.ajax({
                type: 'POST',
                url: "{{url('editSupplies')}}",
                data: {id: id},
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    $('#ProductModal').html("Edit Product");
                    $('#product-modal').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#description').val(res.description);
                    $('#price').val(res.price);
                    $('#stock').val(res.stock);
                    if(res.photo){
                        var imgpath = "{{ asset('storage/photos/') }}/";
                        var imgurl = imgpath + res.photo;
                        $('#image').attr('src', imgurl).show();
                    } else {
                        $('#image').hide();
                    }
                    $('#photo').val('');
                    $('#photo').prop('required', false);

                    
                }
            });
        }

        function deletef(id){
            var confirmationMessage = "Are you sure you want to delete Item ID: " + id + "?";
            if(confirm(confirmationMessage ) == true){
                $.ajax({
                type: 'POST',
                url: "{{url('deleteSupplies')}}",
                data: {id: id},
                dataType: 'json',
                success: function(res){
                    $('#suppliesTable').DataTable().ajax.reload(); 

                }
            });
            }

        }
    
        function add(){            
            $('#ProductForm').trigger("reset");
            $('#product-moal').html("Add Employee");
            $('#product-modal').modal('show');
            $('#image').hide();
            $('#photo').prop('required', true);
            $('#photo').val('');
            $('#id').val('');
        }

        
    
        $('#ProductForm').submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('addSupplies') }}",
                data: formData,
                cache: false,
                contentType: false, 
                processData: false,
                success: function(data) {
                    console.log(data);
                    $("#product-modal").modal('hide');
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled",false);
                    $('#suppliesTable').DataTable().ajax.reload(); 
                    $('#image').hide();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        })
    
         $('#importSuppliesForm').on('submit', function(){
            event.preventDefault();
            $.ajax({
                url:"{{route('importSupplies')}}",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success:function(data){
                    $('#importModal').modal('hide');
                    $('#importSuppliesForm').trigger('reset')
                    $('#suppliesTable').DataTable().ajax.reload();
                }
            });
         });
    
    
    </script>
    
    

</x-app-layout>
