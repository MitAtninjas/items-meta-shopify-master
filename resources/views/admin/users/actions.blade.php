<div class="btn-toolbar">
    <div class="btn-group">
        <a href="{{ route('admin.users.edit', $users->id) }}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="fa fa-pencil-alt"></i></a>
        <a href="{{ route('admin.users.show', $users->id) }}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="Show" data-original-title="Show"><i class="fa fa-eye"></i></a>
        <a href="{{ route('admin.users.change-password', $users->id) }}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="Change Password" data-original-title="Change password"><i class="fa fa-key"></i></a>
        <a href="{{ route('admin.users.destroy', $users->id) }}" class="btn btn-sm btn-primary btn-delete" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i></a>
    </div>
</div>