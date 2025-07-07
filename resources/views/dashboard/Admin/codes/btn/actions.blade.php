{{-- <div class="d-flex">


    <!-- @if ($code-> deleted_at == null)
        <form id="delete-form-{{ $code->id }}" action="{{ route('admin.videos.destroy', $code->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger mx-1  btn-sm">{{ trans('dashboard/general.delete')}}</button>
        </form>
    @else
        <form action="{{ route('admin.restore',$code->id) }}" method="post">
            @csrf

            <button class="btn btn-danger mx-1 btn-sm">{{ trans('dashboard/general.restore')}}</button>


        </form>
    @endif -->

        <a href="" class="btn btn-info btn-sm" style="margin-left: 5px; justify-content: center; align-items: center; display: flex;">{{ trans('dashboard/general.update')}}</a>
        <button class="btn btn-danger mx-1  btn-sm">{{ trans('dashboard/general.delete')}}</button>

</div> --}}


    <script>
        function deleteAdmin(id) {
            if (confirm("Are you sure you want to delete this admin?")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>

