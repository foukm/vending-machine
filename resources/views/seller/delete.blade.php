@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center  pt-4">
        <div class="col-3 pt-5">
            <h2>Seller</h2>
            <div class="pt-1">
                <label>Username: </label><span class="username-text fw-bold"></span>
            </div>
            <div class="pt-5">
                <h4 class="form-label fw-bold">Delete Product</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">Select Product</label>
                    <select id="productId" name="productId" class="form-control bg-opacity-10 border ">
                        <option value="" disabled selected>Select Product</option>
                    </select>
                </div>
                <div class="d-grid">
                    <button class="btn btn-warning delete-btn" type="button">Delete</button>
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

            $('.delete-btn').on('click', function (e){
                e.preventDefault();
                var updateUrl = '{{env('API_URL')}}/products/:id';
                if($('#productId').val() == '' || $('#productId').val() == null){
                    alert('No product selected');
                    return;
                }
                $.ajax({
                    type: 'DELETE',
                    url: updateUrl.replace(':id', $('#productId').val()),
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
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
