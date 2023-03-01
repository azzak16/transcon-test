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
    <div class="card col-md-8">
        <form id="form" action="" method="post">
            @csrf
            <input type="hidden" id="id" value="{{ $datas->id }}">
            <div class="form-group">
                <label for="transaction_no">Transaction No</label>
                <input type="text" class="form-control" name="transaction_no" value="{{ $datas->no_transaction }}" readonly>
            </div>
            <div class="form-group">
                <label for="transaction_date">Transaction Date</label>
                <input type="date" class="form-control" name="transaction_date" value="{{ $datas->transaction_date }}" readonly>
            </div>
            <div class="row">
                <P>Detail Items</P>
            </div>
            <div class="items">
                @if ($datas->transactionDetails)
                    @foreach ($datas->transactionDetails as $items)
                        <div class="group">
                            <input type="hidden" value="{{ $items->id }}" name="id[]" class="id">
                            <div class="form-group">
                                <label for="item_name">Item Name</label>
                                <input type="text" class="form-control item_name" name="item_name[]" value="{{ $items->item }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control quantity"  name="quantity[]" value="{{ $items->quantity }}" readonly>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="float-right">
                <a href="{{ route('transaction.index') }}" class="btn btn-secondary">back</a>
            </div>
        </form>

    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var transaction_id = [];

        $(document).ready(function () {

            $('#tambah').on('click', function () {
                var html = `<div class="group">
                    <a href="#" class="btn btn-danger float-right hapus">X</a>
                    <input type="hidden" value="" name="id[]">
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control item_name" name="item_name[]">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control quantity"  name="quantity[]">
                    </div>
                </div>`;

                $('.items').append(html);
            });

            $(document).on('click', '.hapus', function(){
                $(this).parent().remove();
            });

            $('.hapus-isi').on('click', function () {
                var id = $(this).next().val();

                transaction_id.push(id);

                $(this).parent().remove();
            });

            $('#submit').on('click', function () {

                var id = $('#id').val();
                var url = "{{ route('transaction.update', ":id") }}";
                url = url.replace(':id', id);

                var formData = new FormData($("#form")[0]);
                formData.append('td_id', transaction_id);

                $.ajax({
                    url : url,
                    method : 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success : function(result){
                        alert(result.message);
                        document.location = result.url;
                    },
                    error: function(response) {
                        var response = response.responseJSON;

                        alert(response.message);
                    }
                });
            })
        })

    </script>
</body>
</html>
