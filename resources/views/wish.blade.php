<!doctype html>
<html lang="en">

<head>
    <title>Wish Message</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">

</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Danh sách lời chúc</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-wrap">

                        <button class="btn btn-success" id="accept_all">Duyệt tất cả</button>

                        <button class="btn btn-warning" id="reject_all">Huỷ duyệt tất cả</button>

                        <table class="table table-hover" id="wish-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Tên</th>
                                    <th style="width: 45%;">Lời chúc</th>
                                    <th style="width: 15%;">Trạng thái</th>
                                    <th style="width: 15%;">Ngày tạo</th>
                                    <th style="width: 10%;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($wishes as $wish)
                                    <tr class="alert" role="alert">

                                        <td>
                                            {{ $wish->name }}
                                        </td>
                                        <td>{{ $wish->wish_message }}</td>
                                        <td class="status" style="cursor: pointer;">
                                            <span
                                                class="{{ $wish->wish_status == 1 ? 'active' : 'waiting' }} status-wish"
                                                data-status="{{ $wish->wish_status }}">{{ $wish->wish_status == 1 ? 'Đã duyệt' : 'Chờ duyệt' }}</span>
                                        </td>
                                        <td>{{ $wish->created_at->format('H:i - d-m-Y') }}</td>
                                        <td>
                                            <button type="button" data-id="{{ $wish->id }}"
                                                class="btn btn-danger btn-sm delete-wish">
                                                Xoá
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.19.1/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#wish-table').DataTable({
                order: [[0, 'desc']]
            });

            $(document).on('click', '.delete-wish', function() {
                var id = $(this).data('id');
                var url = "{{ route('wish.destroy', ':id') }}";
                url = url.replace(':id', id);
                Swal.fire({
                    title: "Bạn có chắc chắn muốn xoá không?",
                    text: "Điều này sẽ không thể hoàn tác!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire(
                                    "Deleted!",
                                    "Your file has been deleted.",
                                    "success"
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    "Error!",
                                    "Something went wrong.",
                                    "error"
                                );
                            }
                        });
                    }
                });
            })

            $('.status-wish').on('click', function() {
                var status = $(this).data('status');
                var id = $(this).closest('tr').find('.delete-wish').data('id');
                var url = "{{ route('wish.updateStatus', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PATCH',
                        status: status == 1 ? 0 : 1
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            });

            $('#accept_all').on('click', function() {
                changeStatusAll(1);
            });

            $('#reject_all').on('click', function() {
                changeStatusAll(0);
            });

        });

        function changeStatusAll(status) {
            var url = "{{ route('wish.acceptAll') }}";
            Swal.fire({
                title: "Bạn có chắc chắn muốn cập nhật tất cả?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Có, cập nhật tất cả!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH',
                            status: status
                        },
                        success: function(response) {
                            Swal.fire(
                                "DONE",
                                "Tất cả lời chúc đã được cập nhật.",
                                "success"
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                "Lỗi!",
                                "Something went wrong.",
                                "error"
                            );
                        }
                    });
                }
            });
        }
    </script>

</body>

</html>
