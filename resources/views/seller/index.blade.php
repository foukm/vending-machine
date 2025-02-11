@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center align-items-center flex-column pt-4">
        <div class="col-6 pt-5">
            <h2>Seller</h2>
            <div class="pt-1 pb-5">
                <label>Username: </label><span class="username-text fw-bold"></span>
            </div>
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
        <div class="col-3 pt-5">

            <div class="pt-5">
                <div class="d-grid">
                    <a href="{{route('seller.products.add')}}" class="btn btn-primary add-btn" type="button">Add Product</a>
                </div>
            </div>
            <div class="pt-2">
                <div class="d-grid">
                    <a href="{{route('seller.products.edit')}}" class="btn btn-info add-btn" type="button">Update Product</a>
                </div>
            </div>
            <div class="pt-2">
                <div class="d-grid">
                    <a href="{{route('seller.products.delete')}}" class="btn btn-warning add-btn" type="button">Delete Product</a>
                </div>
            </div>
            <div class="d-grid mt-5">
                <a href="{{route('seller.edit')}}" class="btn btn-secondary edit-user-btn" type="button">Edit User</a>
            </div>
            <div class="d-grid mt-2">
                <button class="btn btn-warning delete-user-btn" type="button">Delete User</button>
            </div>
            <div class="d-grid mt-2">
                <button class="btn btn-danger logout-btn" type="button">Logout</button>
            </div>
        </div>

    </div>
@endsection
@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.username-text').text(localStorage.getItem('username'));

            $.ajax({
                type: 'GET',
                url: '{{env('API_URL')}}/user/products',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                },
                success: function (response) {
                    if(response.success){
                        fillArray(response.data);
                    }
                },
                error: function (data){
                    if(data.responseJSON.hasOwnProperty('errors')){
                        alert(JSON.stringify(data.responseJSON.errors));
                    }else{
                        alert(data.responseJSON.error);
                    }
                },
                fail: function (data) {
                    if(data.responseJSON.hasOwnProperty('errors')){
                        alert(JSON.stringify(data.responseJSON.errors));
                    }else{
                        alert(data.responseJSON.error);
                    }
                }
            });

            $('.logout-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/user/logout',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        alert(response.message);
                        window.location = '{{route('login')}}';
                    },
                    error: function (data) {
                        alert(data.responseJSON.message);
                    },
                    fail: function (data) {
                        alert(data.responseJSON.message);
                    },
                    dataType: "json",
                });
            })

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

            $('.delete-user-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'DELETE',
                    url: '{{env('API_URL')}}/user/delete',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        alert(response.message);
                        localStorage.removeItem('deposit');
                        localStorage.removeItem('token');
                        localStorage.removeItem('username');
                        window.location = '{{route('login')}}';
                    },
                    error: function (data) {
                        if(data.responseJSON.hasOwnProperty('errors')){
                            alert(JSON.stringify(data.responseJSON.errors));
                        }else if(data.responseJSON.hasOwnProperty('error')){
                            alert(data.responseJSON.error);
                        }else{
                            alert(data.responseJSON.message);
                        }
                    },
                    fail: function (data) {
                        if(data.responseJSON.hasOwnProperty('errors')){
                            alert(JSON.stringify(data.responseJSON.errors));
                        }else if(data.responseJSON.hasOwnProperty('error')){
                            alert(data.responseJSON.error);
                        }else{
                            alert(data.responseJSON.message);
                        }
                    },
                    dataType: "json",
                });
            })
        });
    </script>
@endpush
