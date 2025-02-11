@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center  pt-4">
        <div class="col-6 pt-5">
            <h3>Products</h3>
            <table class="table table-bordered products-list">
                <thead>
                    <th>ID</th>
                    <th>productName</th>
                    <th>amountAvailable</th>
                    <th>Cost</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
@endsection
@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: '{{env('API_URL')}}/products',
                success: function (response) {
                    if(response.success){
                        fillArray(response.data);
                    }else{
                        alert(response.errors);
                    }
                },
                error: function (e, ts, et){
                    console.log(e);
                    console.log(ts);
                    console.log(et);
                },
                fail: function (e, ts, et) {
                    console.log(e);
                    console.log(ts);
                    console.log(et);
                }
            });

            function fillArray(data){
                var tableBody = $('table tbody');
                $.each(data, function(key,value){
                    var row = '';
                    row += '<tr>';
                    row += '<td>' + value['id'] +'</td>';
                    row += '<td>' + value['productName'] +'</td>';
                    row += '<td>' + value['amountAvailable'] +'</td>';
                    row += '<td>' + value['cost'] +'</td>';
                    row += '</tr>';
                    tableBody.append(row);
                })
            }
        });
    </script>
@endpush
