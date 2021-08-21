@if ($errors->any())
    <div class="border border-danger text-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="name">Name</label>
        <input type="text" class="form-control text-capitalize" id="name" name="name" placeholder="Full Name"
        value="@if(isset($user)){{ $user->name }}@else{{ old('name') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="username@example.com"
        value="@if(isset($user)){{ $user->email }}@else{{ old('email') }}@endif" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="photo">Photo</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/png, image/jpeg">
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="photo">Current Photo</label>
        @if (isset($user->photo_url))
            <img height="42" width="42" src="{{ asset('storage/profile_picture/' . $user->photo_url) }}" alt='profile photo'
            class="inline w-9 h-9 pr-1" />
        @else
            <span class="border border-red-500">
                No photo provided.
            </span>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="role">Role</label>
        <select class="form-control js-example-basic-single" id="role" name="role" required>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @if(isset($user)) @if($user->roles->first()->id == $role->id) selected @endif @endif>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password"
        placeholder="Password" required>
    </div>
    <div class="form-group col-sm-3">
        <label class="text-capitalize" for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
        placeholder="Confirm password" required>
    </div>
</div>

<div class="form-group">
    <input type="submit" class="btn btn-success" value="@if(isset($due)) Update @else Create @endif">
    <a class="btn btn-danger ml-3" href="{{ route('useraccounts.index') }}">Cancel</a>
</div>
