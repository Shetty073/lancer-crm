<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes, HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_url',
        'no_of_enquiries_assigned',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function firstName()
    {
        return explode(' ', $this->name)[0];
    }

    public function displayName()
    {
        $full_name = explode(' ', $this->name);
        return $full_name[0] . ' ' . ucfirst(substr(end($full_name), 0, 1)) . '.';
    }

    public function adminlte_image()
    {
        if($this->photo_url == null or $this->photo_url == "") {
            return 'https://i.pravatar.cc/150?u=' . $this->email;
        } else {
            return asset('storage/profile_picture/' . $this->photo_url);
        }
    }

    // mutator for hashing password
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
