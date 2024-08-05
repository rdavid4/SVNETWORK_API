<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'company_id',
        'rate',
        'user_id',
        'edited'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function reply(){
        return $this->hasOne(ReviewReply::class);
    }
    public function getDateHumansAttribute()
    {
        $date1 = Carbon::parse($this->updated_at);
        $date2 = Carbon::now(); // Represents the current date and time
        $diff = $date1->diffForHumans($date2);
        return  $diff;
    }
    public function wasUpdated(){
        return $this->edited;
    }
}
