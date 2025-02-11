@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center align-items-center pt-4 flex-column">
        <div class="col-3 pt-5">
            <h3>Vending Machine</h3>
            <form action="#" method="POST" class="grid grid-3 login-form">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" id="username" name="username" class="form-control bg-opacity-10 border ">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="password" class="form-control bg-opacity-10 border ">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
                <div class="d-grid mt-2">
                    <button class="btn btn-danger logout-all-btn" type="button">Logout All for User</button>
                </div>
            </form>
        </div>
        <div class="col-3 pt-5">
            <div class="d-grid mt-3">
                <a href="{{route('register')}}" class="btn btn-light" type="button">Register</a>
            </div>
            <div class="d-grid mt-3">
                <a href="{{route('products.index')}}" class="btn btn-info" type="button">Show all products</a>
            </div>
        </div>
    </div>
@endsection
@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.login-form').on('submit',function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/login',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "username": $('#username').val(),
                        "password": $('#password').val()
                    },
                    success: function (response) {
                        localStorage.setItem('username',response.username);
                        localStorage.setItem('token',response.token);
                        localStorage.setItem('deposit',response.deposit);
                        if(response.role === 'buyer'){
                            window.location = '{{route('buyer.index')}}';
                        }else if(response.role === 'seller'){
                            window.location = '{{route('seller.index')}}';

                        }
                    },
                    error: function (data) {
                        alert(data.responseJSON.error);

                    },
                    fail: function (data) {
                        alert(data.responseJSON.error);

                    },
                    dataType: "json",
                });
            })


            $('.logout-all-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/logout/all',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "username": $('#username').val()
                    },
                    success: function (response) {
                        alert(response.message);
                    },
                    error: function (data) {
                        if(data.responseJSON.hasOwnProperty('message')) {
                            alert(data.responseJSON.message);
                        }else{
                            alert(data.responseJSON.error);
                        }
                    },
                    fail: function (data) {
                        if(data.responseJSON.hasOwnProperty('message')) {
                            alert(data.responseJSON.message);
                        }else{
                            alert(data.responseJSON.error);
                        }
                    },
                    dataType: "json",
                });
            })
        });
    </script>
@endpush
