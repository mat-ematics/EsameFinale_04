<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Access extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $table = 'accesses';

    protected $fillable = [
        'ip',
        'attempts',
        'last_attempt_at',
    ];

    /* --------- METODI ---------*/

    public function addError() : bool
    {
        return $this->update([
            'attempts' => DB::raw('attempts + 1'),
            'last_attempt_at' => now(),
        ]);
    }

    /* --------- METODI STATICI ---------*/

    public static function getAccessByIp(string $ip) : ?self
    {
        return static::where('ip', $ip)->first();
    }

    public static function createAccess(string $ip) : ?self
    {
        return static::create([
            'ip' => $ip,
            'attempts' => 0,
        ]);
    }

    public static function deleteAccess(string $ip) : bool
    {
        return static::where('ip', $ip)->delete();
    }
}
