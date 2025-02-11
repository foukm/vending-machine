@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center  pt-4">
        <div class="col-3 pt-5">
            <h2>Seller</h2>
            <div class="pt-5">
                <label>Username: </label><span class="username-text fw-bold"></span>
            </div>
            <div class="pt-5">
                <h4 class="form-label fw-bold">Add Product</h4>
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
                    <button class="btn btn-primary add-btn" type="button">Add</button>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.username-text').text(localStorage.getItem('username'));

            $('.add-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/products',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
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
