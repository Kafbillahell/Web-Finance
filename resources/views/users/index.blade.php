@extends('layouts.default')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">User</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
                    </ol>
                </nav>
            </div>  
        </div>
        <div class="col-5 align-self-center">
            <div class="app-search float-right">
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-rounded">Add User</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User List</h4>
                    <div class="table-responsive">
                        <table class="table table-striped" id="user-table">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">#</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable with AJAX
        $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("user.index") }}',
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'created_at', name: 'created_at' },
                { 
                    data: 'action', 
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#add-user-btn').click(function(e) {
            e.preventDefault();
            $('#user-modal').modal('show');
        });

        $('#user-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{{ route('user.store') }}",
                data: $(this).serialize(),
                success: function(data) {
                    $('#user-modal').modal('hide');
                    $('#user-table').DataTable().ajax.reload();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        function editUser(id) {
            $.ajax({
                type: 'GET',
                url: `{{ route('user.edit', ':id') }}`.replace(':id', id),
                success: function(data) {
                    $('#user-modal .modal-title').text('Edit User');
                    $('#user-modal #name').val(data.name);
                    $('#user-modal #email').val(data.email);
                    $('#user-modal #role').val(data.role);
                    $('#user-modal #password').val('');
                    $('#user-modal #password_confirmation').val('');
                    $('#user-modal form').attr('action', `{{ route('user.update', ':id') }}`.replace(':id', id));
                    $('#user-modal').modal('show');
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    type: 'DELETE',
                    url: `{{ route('user.destroy', ':id') }}`.replace(':id', id),
                    success: function(data) {
                        $('#user-table').DataTable().ajax.reload();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        }
    });
</script>
@endsection