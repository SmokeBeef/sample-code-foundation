<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends User implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = "admins";
    protected $primaryKey = "admin_id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        "admin_username",
        "admin_password"

    ];
    protected $hidden = [
        "admin_password"
    ];
    protected $casts = ["admin_password" => "hashed"];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getAuthPassword()
    {
        return $this->admin_password;
    }

    public static function deleteOrNull($id): null|array
    {
        try {
            $admin = self::find($id);
            if (!$admin) {
                return null;
            }
            $admin->delete();
            return $admin->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }

    public static function createOrNull($data): null|array
    {
        try {
            $isUsernameTaken = self::where("admin_username", $data["admin_username"])->first();
            if ($isUsernameTaken) {
                return null;
            }
            $result = self::create($data);
            return $result->toArray();
        } catch (Exception $err) {
            throw $err;
        }
    }

}
