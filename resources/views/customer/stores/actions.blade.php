<div class="btn-toolbar">
    <div class="btn-group">
        <a href="{{ route('customer.stores.edit', $stores) }}" class="btn btn-sm btn-primary js-tooltip-enabled"
            data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i
                class="fa fa-pencil-alt"></i></a>

        <a href="{{ route('customer.stores.destroy', $stores) }}" class="btn btn-sm btn-primary btn-delete"
            data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
    </div>
</div>
