<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <div class="card">
        <a href="{{ route('transaction.create') }}" class="btn btn-success">Create</a>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Transaksi</th>
                <th>Total Item</th>
                <th>Total Quantity</th>
                <th>Action</th>
            </tr>
            @forelse ($datas as $data)
            <tr id="row_{{ $data->id }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->no_transaction }}</td>
                <td>{{ $data->total_item }}</td>
                <td>{{ $data->total_quantity }}</td>
                <td>
                    <a href="{{ route('transaction.show', $data->id) }}">view</a> |
                    <a href="{{ route('transaction.edit', $data->id) }}">edit</a> |
                    <a href="#" class="delete" data-id="{{ $data->id }}">delete</a>
                </td>
            </tr>
            @empty
                <h3 class="text-center">Data kosong coba buat baru</p>
            @endforelse
        </table>
    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click','.delete',function(){

            var id = $(this).data('id');
            var url = "{{ route('transaction.delete', ":id") }}";
            url = url.replace(':id', id);

                    $.ajax({
                        url : url,
                        method: 'delete',
                        data: {id:id},
                        processData: false,
                        contentType: false,
                        success : function(result){
                            alert(result.message);

                            $(`#row_${result.id}`).remove();
                        },
                        error: function(response) {
                            var response = response.responseJSON;

                            alert(response.message);
                        }
                    });


        });

    </script>
</body>
</html>
