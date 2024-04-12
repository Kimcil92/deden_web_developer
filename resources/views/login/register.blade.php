@extends('login.part.area')

@section('section')

    <h1>User Register</h1>
    <div class="main-agileinfo">
        <div class="agileits-top">
            <form action="{{ route('register') }}" method="post">
                @csrf
                <label>
                    <input class="text" type="text" name="name" placeholder="Name" required="">
                </label>
                <label>
                    <input class="text email" type="email" name="email" placeholder="Email" required="">
                </label>
                <label>
                    <input class="text" type="text" name="phone_number" placeholder="Phone Number" required="">
                </label>
                <label>
                    <input class="text" type="text" name="address" placeholder="Address" required="">
                </label>
                <label>
                    <input class="text" type="text" name="sim" placeholder="SIM" required="">
                </label>
                <label>
                    <input class="text" type="password" name="password" placeholder="Password" required="">
                </label>
                <label>
                    <input class="text w3lpass" type="password" name="password_confirmation" placeholder="Confirm Password" required="">
                </label>

                <input type="submit" value="SIGNUP">
            </form>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <p>Have an Account? <a href="/login"> Login Now!</a></p>
        </div>
    </div>

@endsection
