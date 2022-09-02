@extends('layouts.backend')

@section('title', 'User List')

@push('css_after')
<link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">User List</h3>
        <div class="block-options">
            <a href="{{ route('admin.users.create') }}" class="btn btn-md btn-primary">
              <i class="fa fa-plus mr-2"></i>Add User
            </a>
        </div>
    </div>
    <div class="block-content block-content-full table-responsive">
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>
                        <select name="role_filter" id="role_filter" class="form-control form-control-alt form-control-sm">
                            <option value="">Select Role</option>
                            @foreach(config('constants.roles') as $key => $role)
                                <option value="{{ $key }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th>Member Since</th>
                    <th>Status</th>
                    <th class="w-20 text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('js_after')
<script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript">
    var dTable = '';
    jQuery(function() {
        
        var selectedRole = jQuery('#role_filter').val()
        loadPartialDataTable(selectedRole);

        jQuery(document).on('change', '#role_filter', function() {
            let role = jQuery(this).val();
            jQuery('#users-table').DataTable().destroy();
            loadPartialDataTable(role);
        });

        function loadPartialDataTable(role = '') {
            dTable = jQuery('#users-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: !1,
                order: [[ 1, "asc" ]],
                language: {
                    searchPlaceholder: "Search by email or name.."
                },
                ajax: {
                    url: "{{ route('admin.users.data') }}",
                    data: { role: role },
                    type: "POST"
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, sClass : 'text-center' },
                ]   
            });
        } 
    });

    function loadDataTable() {
       dTable.ajax.reload( null, false );
       
        
    }
</script>
@endpush