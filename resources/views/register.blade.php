@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center align-items-center pt-4 flex-column">
        <div class="col-3 pt-5">
            <h3>Register</h3>
            <form action="#" method="POST" class="grid grid-3 register-form pt-3">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" id="username" name="username" class="form-control bg-opacity-10 border ">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="password" class="form-control bg-opacity-10 border ">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select id="role" name="role" class="form-control bg-opacity-10 border ">
                        <option value="seller">Seller</option>
                        <option value="buyer">Buyer</option>
                    </select>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.register-form').on('submit',function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/user',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "username": $('#username').val(),
                        "password": $('#password').val(),
                        "role": $('#role').val()
                    },
                    success: function (response) {
                        alert(response.message);
                        window.location = '{{route('login')}}';
                    },
                    error: function (data) {
                        if(data.responseJSON.hasOwnProperty('errors')){
                            alert(JSON.stringify(data.responseJSON.errors));
                        }else{
                            alert(data.responseJSON.error);
                        }
                    },
                    fail: function (data) {
                        alert(data.responseJSON.error);

                    },
                    dataType: "json",
                });
            })

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
                    },
                    error: function (data) {
                        console.log(data);
                    },
                    fail: function (data) {
                        console.log(data);
                    },
                    dataType: "json",
                });
            })
        });
    </script>
@endpush
