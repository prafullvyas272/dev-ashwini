<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relation to load leasing periods
     */
    public function leasingPeriods()
    {
        return $this->hasMany(LeasingPeriod::class, 'device_id');
    }

    /**
     * Relation to load device type
     *
     */
    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    /**
     * Relation to load device owner details
     */
    public function deviceOwnerDetails()
    {
        return $this->hasOne(DeviceOwnerDetail::class, 'device_id');
    }
}
