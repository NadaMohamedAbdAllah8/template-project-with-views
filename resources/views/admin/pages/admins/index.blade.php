@extends('layouts.master')
@extends('admin.layouts.header')

@section('title')
    {{ $title ?? 'Admins' }}
@endsection

@section('content')
    <div class="container">
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary actionbtn"
            style="margin-bottom:3rem; margin-top:2rem;width:20rem; ">
            Create Admin
        </a>

        <table class="table yajra-datatable table-striped" id="admins">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Name</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#admins').DataTable({
            "processing": true,
            "serverSide": true,
            'paging': true,
            'info': true,
            "ajax": "{{ route('admin.admins.data') }}",
            // "language": {
            //     "emptyTable": "No data available in table. Check back later"
            // },
            "columnDefs": [{
                    "targets": 0, // target the first column (id)
                    "data": "id",
                },
                {
                    "targets": 1, // target the second column (email)
                    "render": function(data, type, row) {
                        let url = "{{ route('admin.admins.show', 'id') }}";
                        let id = (row['id']);
                        url = url.replace('id', id);
                        return '<a  href=' + url + '>' + row['email'];
                    }
                },
                {
                    "targets": 2, // target the third column (name)
                    "data": "name"
                },
                {
                    "targets": 3, // target the fourth column (country)
                    "data": "created_at"
                },
                {
                    "targets": 4, // target the fifth column (action buttons)
                    "data": null,
                    "render": function(data, type, row) {
                        let url = "{{ route('admin.admins.edit', 'id') }}";
                        let id = (row['id']);
                        url = url.replace('id', id);
                        return '<a class="btn btn-primary"  href=' + url +
                            '><i class="fa fa-edit"></i></a> <a style="color:#fff" class="btn btn-danger delete" data-content="' +
                            id + '"><i class="fa fa-trash"></i></a>';
                    },
                    "sortable": false,
                    "searchable": false,
                },
            ],
        });

        // delete admin data
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $("#admins").on('click', '.delete', function() {
            let content = $(this).data("content");
            let delete_url = "{{ route('admin.admins.destroy', 'id') }}";
            delete_url = delete_url.replace('id', content);
            $.ajax({
                url: delete_url,
                method: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    id: content,
                    _method: "delete"
                },
                dataType: 'JSON',
                beforeSend: function() {},
                success: function(response) {
                    $("#admins").DataTable().ajax.reload();
                    swal({
                        title: "Deleted",
                        text: "Deleted successfully",
                        icon: "success",
                        buttons: false,
                        timer: 1700
                    });
                },
                error: function(response) {
                    console.log('response=' + response);
                    let errorObj = JSON.parse(response.responseText);
                    console.log('errorObj=' + errorObj);
                    let error = errorObj.error;
                    console.log('error=' + error);
                    swal({
                        title: "Error",
                        text: error,
                        icon: "error",
                        buttons: false,
                        timer: 1700
                    });
                }
            });
        });
    </script>
@endsection
