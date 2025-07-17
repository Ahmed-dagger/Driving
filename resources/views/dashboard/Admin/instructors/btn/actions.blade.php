<div class="d-flex">

    @if (is_null($instructor->deleted_at))
        {{-- Delete Form --}}
        <form id="delete-form-{{ $instructor->id }}" action="{{ route('admin.instructors.destroy', $instructor->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm mx-1">{{ trans('dashboard/general.delete') }}</button>
        </form>
    @else
        {{-- Restore Form --}}
        <form action="{{ route('admin.instructors.restore', $instructor->id) }}" method="POST">
            @csrf
            <button class="btn btn-warning btn-sm mx-1">{{ trans('dashboard/general.restore') }}</button>
        </form>
    @endif

    {{-- Edit Button --}}
    <a href="{{ route('admin.instructors.edit', $instructor->id) }}" class="btn btn-info btn-sm mx-1">
        {{ trans('dashboard/general.update') }}
    </a>

</div>



    <script>
        function deleteAdmin(id) {
            if (confirm("Are you sure you want to delete this admin?")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>

