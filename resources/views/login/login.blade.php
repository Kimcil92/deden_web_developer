@extends('login.part.area')

@section('section')

    <h1>Rental Mobil</h1>
    <div class="main-agileinfo">
        <div class="agileits-top">
            <form id="login" action="{{ route('login') }}" method="post">
                @csrf
                <label>
                    <input class="text" type="email" name="email" placeholder="Email" required="">
                </label>
                <br>
                <label>
                    <input class="text" type="password" name="password" placeholder="Password" required="">
                </label>

                <input type="submit" value="SIGN IN">
            </form>
            <p>Forget Password? <a href="/reset-password"> Reset Password</a></p>
            <br>
            <br>
            <p>Don't have an Account? <a href="/register"> Register Now!</a></p>
        </div>
    </div>
@endsection
