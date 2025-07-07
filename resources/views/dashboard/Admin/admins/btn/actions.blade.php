<div class="d-flex">
    @if ($admin->deleted_at == null)
        <!-- Trigger the delete confirmation modal -->
        <button type="button" class="btn btn-danger mx-1" onclick="confirmDeleteAdmin({{ $admin->id }})">
            {{ trans('dashboard/general.delete') }}
        </button>

        <!-- Hidden delete form (this will be submitted upon confirmation) -->
        <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.destroy', $admin->id) }}" method="post" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @else
        <form action="{{ route('admin.restore', $admin->id) }}" method="post">
            @csrf
            <button class="btn btn-success mx-1">{{ trans('dashboard/general.restore') }}</button>
        </form>
    @endif

    <a href="{{ route('admin.admins.edit', $admin->id) }}">
        <button class="btn btn-info">{{ trans('dashboard/general.update') }}</button>
    </a>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAdminModal" tabindex="-1" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAdminModalLabel">Delete Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this admin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteAdminBtn">Delete</button>
            </div>
        </div>
    </div>
</div>


<script>

    let selectedAdminId = null; // Variable to store the selected admin ID

// Function to trigger the delete confirmation modal
function confirmDeleteAdmin(adminId) {
    selectedAdminId = adminId; // Set the selected admin ID
    $('#deleteAdminModal').modal('show'); // Show the confirmation modal
}

// Handle the confirm delete button click
$('#confirmDeleteAdminBtn').on('click', function() {
    if (selectedAdminId) {
        // Submit the delete form for the selected admin
        document.getElementById('delete-form-' + selectedAdminId).submit();
        $('#deleteAdminModal').modal('hide'); // Hide the modal after submission
    }
});
</script>