<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User List') }}
        </h2>
    </x-slot>

    
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal" style="margin-left:2%; margin-top:2%;">
        Import Users
    </button>
    
    <!-- Export Button -->
    <a href="exportUsers" type="button" class="btn btn-success" style="margin-left:2%; margin-top:2%;">Export Users</a>
    

    <div class="card" style="margin: 2%;">
        <div class="card-header">
            Users
        </div>
            <div class="card-body">
                <table class="table table-bordered" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                </table>
            </div>
        </div>
    
    </div>
    

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="importUsersForm" action="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Users</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" id="importUsers" name="importUsers" accept=".csv" 
                        class="btn btn-primary" style="margin-left:2%; margin-top:2%;" placeholder="Import Users"></a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#usersTable').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: "{{url('usersTable')}}",
                columns:[
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'address', name: 'address'},
                    {data: 'phone', name: 'phone'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},

                ]

            });

            $('#importUsersForm').on('submit', function(event){
                event.preventDefault();
                $.ajax({
                    url:"{{route('import.users')}}", 
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data){
                        $('#importModal').modal('hide');
                        $('#importUsersForm').trigger('reset')
                    }

                });
            });

        });

    </script>
</x-app-layout>