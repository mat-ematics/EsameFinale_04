<?php

namespace App\Models\User;

use App\Helpers\AppHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Password extends Model
{
    protected $table = 'passwords';

    protected $fillable = [
        'user_id',
        'password',
        'salt',
    ];

    /* --------- RELAZIONI ---------*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* --------- METODI ---------*/

    public function checkPassword(#[\SensitiveParameter] string $password) : bool
    {
        $combined = hash('sha256', $this->salt . $password);
        return Hash::check($combined, $this->password);
    }

    public function checkPasswordLogin(string $passwordHash) : bool {
        return $this->password === $passwordHash;
    }

    /* --------- METODI STATICI ---------*/
    public static function getPasswordHash(
        #[\SensitiveParameter] ?string $password,
        string $salt = '',
    ) : string
    {
        return $password ? hash("sha256", $salt . $password) : null;
    }
}