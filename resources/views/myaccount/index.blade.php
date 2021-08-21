@extends('adminlte::page')

@section('title', 'My Account')

@section('content_header')
    <h1>My Account</h1>
    @if ($errors->any())
        <div class="border border-danger text-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="border border-danger text-danger">
            <ul>
                <li>{{ Session::get('success') }}</li>
            </ul>
        </div>
    @endif
@stop

@section('content')
    <div class="card px-3 py-2">
        <form method="post" action="{{ route('myaccount.update', ['id' => auth()->user()->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="name">Name</label>
                    <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
                    value="{{ auth()->user()->name }}" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="username@example.com"
                    value="{{ auth()->user()->email }}" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="photo">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/png, image/jpeg">
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="photo">Current Photo</label>
                    @if (isset(auth()->user()->photo_url))
                        <img height="42" width="42" src="{{ asset('storage/profile_picture/' . auth()->user()->photo_url) }}" alt='profile photo'
                        class="inline w-9 h-9 pr-1" />
                    @else
                        <span class="border border-red-500">
                            No photo provided.
                        </span>
                    @endif
                </div>
            </div>

            @error('email')
                <div class="border border-danger text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Update">
                <a class="btn btn-danger ml-3" href="{{ route('dashboard.index') }}">Cancel</a>
            </div>
        </form>

        <form method="post" action="{{ route('myaccount.changepassword', ['id' => auth()->user()->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password"
                    placeholder="Current password" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                    placeholder="New password" required>
                </div>
                <div class="form-group col-sm-3">
                    <label class="text-capitalize" for="password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm new password" required>
                </div>
            </div>

            @error('email')
                <div class="border border-danger text-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Update">
                <a class="btn btn-danger ml-3" href="{{ route('dashboard.index') }}">Cancel</a>
            </div>
        </form>

    </div>
@stop

@section('css')
@stop

@section('js')
@stop
