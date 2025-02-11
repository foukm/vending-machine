@extends('app')

@section('content')
    <div class="col-12 d-flex justify-content-center  pt-4">
        <div class="col-3 pt-5">
            <h2>Edit Buyer</h2>
            <div class="pt-5">
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" id="username" name="username" class="form-control bg-opacity-10 border">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="username" class="form-control bg-opacity-10 border ">
                </div>
                <input type="hidden" id="role" name="role" class="form-control bg-opacity-10 border ">

                <div class="d-grid">
                    <button class="btn btn-primary update-btn" type="button">Update</button>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#username').val(localStorage.getItem('username'));
            $('#role').val('buyer');

            $('.update-btn').on('click', function (e){
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{env('API_URL')}}/user',
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", "Bearer " + localStorage.getItem('token'));
                    },
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": "PUT",
                        "username": $('#username').val(),
                        "password": $('#password').val(),
                        "role": $('#role').val(),
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
