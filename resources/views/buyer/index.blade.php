@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center  pt-4">
        <div class="col-3 pt-5">
            <h2>Buyer</h2>
            <div class="pt-5">
                <label>Username: </label><span class="username-text fw-bold"></span>
                <label class="ps-4">Deposit available: </label><span class="amount-text fw-bold"></span>
            </div>
            <div class="pt-5">
                <h4 class="form-label fw-bold">Deposit Money</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Amount</label>
                    <input type="text" id="amount" name="amount" class="form-control bg-opacity-10 border ">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary deposit-btn" type="button">Deposit</button>
                </div>
            </div>

            <div class="pt-5">
                <h4 class="form-label fw-bold">Buy Products</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Amount</label>
                    <input type="text" id="amount-buy" name="amount-buy" class="form-control bg-opacity-10 border ">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Product</label>
                    <select id="productId" name="productId" class="form-control bg-opacity-10 border">
                        <option value="" disabled selected>Select Product</option>
                    </select>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary buy-btn" type="button">Buy</button>
                </div>
            </div>

            <div class="d-grid mt-5">
                <a href="{{route('buyer.edit')}}" class="btn btn-secondary edit-user-btn" type="button">Edit User</a>
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
            $('.amount-text').text(localStorage.getItem('deposit'));
            $.ajax({
                type: 'GET',
                url: '{{env('API_URL')}}/products',
                success: function (response) {
                    if(response.success){
                        fillSelect(response.data);
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

            function fillSelect(data){
                var selection = $('#productId');
                $.each(data, function(key,value){
                    var row = '';
                    row += '<option value="'+value['id']+'">';
                    row += value['productName'] +' (Available: '+value['amountAvailable']+') - (Price per item: '+ value['cost'] +')';
                    row += '</option>';
                    selection.append(row);
                })
            }

            $('.deposit-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/deposit',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "amount": $('#amount').val()
                    },
                    success: function (response) {
                        localStorage.setItem('deposit', response.deposit);
                        $('.amount-text').text(localStorage.getItem('deposit'));
                        $('#amount').val('')
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

            $('.buy-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/buy',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "productId": $('#productId').val(),
                        "amount": $('#amount-buy').val()
                    },
                    success: function (response) {
                        alert(response.message + ' ' +response.purchased +' for '+ response.spent +"\nYour change is "+ response.change);
                        resetDeposit();
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

            function resetDeposit(){
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/reset',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        alert(response.message);
                        localStorage.setItem('deposit',0);
                        window.location = '{{route('buyer.index')}}';

                    },
                    error: function (data) {
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
                    },
                    dataType: "json",
                });
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
