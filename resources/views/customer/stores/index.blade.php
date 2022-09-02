@extends('layouts.backend')

@section('title', 'Store List')

@push('css_after')
<link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
@endpush

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">Store List</h3>
        <div class="block-options">
            <a href="{{ route('customer.stores.create') }}" class="btn btn-md btn-primary">
                <i class="fa fa-plus mr-2"></i>Add Store
            </a>
        </div>
    </div>
    <div class="block-content block-content-full table-responsive">
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="stores-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Store Url</th>
                    <th>App Name</th>
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
        
        loadPartialDataTable();

        function loadPartialDataTable() {
            dTable = jQuery('#stores-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: !1,
                order: [[ 1, "asc" ]],
                language: {
                    searchPlaceholder: "Search by email or name.."
                },
                ajax: {
                    url: "{{ route('customer.stores.data') }}",
                    type: "POST"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'store_url', name: 'store_url' },
                    { data: 'app_name', name: 'app_name' },
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