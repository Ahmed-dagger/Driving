<div class="d-flex">

    @if (is_null($rating->deleted_at))
        {{-- Delete Form --}}
        <form id="delete-form-{{ $rating->id }}" action="{{ route('admin.ratings.destroy', $rating->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm mx-1">{{ trans('dashboard/general.delete') }}</button>
        </form>
    @else
        {{-- Restore Form --}}
        <form action="{{ route('admin.ratings.restore', $rating->id) }}" method="POST">
            @csrf
            <button class="btn btn-warning btn-sm mx-1">{{ trans('dashboard/general.restore') }}</button>
        </form>
    @endif

    {{-- Edit Button --}}
    <a href="{{ route('admin.ratings.edit', $rating->id) }}" class="btn btn-info btn-sm mx-1">
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

