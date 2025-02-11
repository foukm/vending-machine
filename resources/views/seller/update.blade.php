@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center  pt-4">
        <div class="col-3 pt-5">
            <h2>Seller</h2>
            <div class="pt-1">
                <label>Username: </label><span class="username-text fw-bold"></span>
            </div>
            <div class="pt-5">
                <h4 class="form-label fw-bold">Update Product</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Product</label>
                    <select id="productId" name="productId" class="form-control bg-opacity-10 border ">
                        <option value="" disabled selected>Select Product</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input type="text" id="productName" name="productName" class="form-control bg-opacity-10 border ">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Cost</label>
                    <input type="number" id="cost" name="cost" class="form-control bg-opacity-10 border ">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Amount</label>
                    <input type="number" id="amountAvailable" name="amountAvailable" class="form-control bg-opacity-10 border ">
                </div>
                <div class="d-grid">
                    <button class="btn btn-info update-btn" type="button">Update</button>
                </div>
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
                        fillSelect(response.data);
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

            $('#productId').change(function(){
                $.ajax({
                    type: 'GET',
                    url: '{{env('API_URL')}}/products/'+$(this).val(),
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    success: function (response) {
                        if(response.success){
                            $('#productName').val(response.data.productName);
                            $('#amountAvailable').val(response.data.amountAvailable);
                            $('#cost').val(response.data.cost);
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
            });

            $('.update-btn').on('click', function (e){
                e.preventDefault();
                if($('#productId').val() == '' || $('#productId').val() == null){
                    alert('No product selected');
                    return;
                }
                var updateUrl = '{{env('API_URL')}}/products/:id';
                $.ajax({
                    type: 'POST',
                    url: updateUrl.replace(':id', $('#productId').val()),
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method" : 'PUT',
                        "productName": $('#productName').val(),
                        "amountAvailable": $('#amountAvailable').val(),
                        "cost": $('#cost').val(),
                    },
                    success: function (response) {
                        alert(response.message);
                        window.location = '{{route('seller.index')}}';
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
            });
        });
    </script>
@endpush
