<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;
class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'zipcode_id',
        'service_id',
        'state_iso',
        'acepted',
        'company_id'
    ];

    protected static function boot()
    {
        parent::boot();

        // Registering the creating event
        static::creating(function ($model) {
            $model->uuid = Str::uuid(); // Genera un UUID único
        });
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function client():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function service():BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
    public function getDateHumansAttribute()
    {
        $date1 = Carbon::parse($this->created_at);
        $date2 = Carbon::now(); // Represents the current date and time
        $diff = $date1->diffForHumans($date2);
        return  $diff;
    }
    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getLocationAttribute(){
        return Zipcode::find($this->zipcode_id);
    }


}
