<div class="card">
    <form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="card-header">
            <h4>Edit Profile</h4>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') ?? auth()->user()->name }}" required/>
                <p class="text-danger">{{ $errors->first('name') }}</p>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="{{ old('email') ?? auth()->user()->email }}" required/>
                <p class="text-danger">{{ $errors->first('email') }}</p>
            </div>
            <div class="form-group">
                <label for="avatar" class="control-label required">Avatar</label>
                <input class="form-control" maxlength="500" accept=".jpg, .jpeg, .png"
                    name="profile_photo_path" type="file" id="profile_photo_path">
                <p class="text-info">Kosongkan jika tidak ingin mengganti avatar</p>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-secondary" type="submit">
                {{ __('Update Profile') }}
            </button>
        </div>
    </form>
</div>