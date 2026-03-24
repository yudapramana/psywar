<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingItem extends Model
{
    protected $fillable = [
        'participant_category_id',
        'package_type',
        'workshop_quota',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function participantCategory()
    {
        return $this->belongsTo(ParticipantCategory::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function getPackageLabelAttribute()
    {
        // PACKAGE 4 (NURSE)
        if (!$this->includes_symposium && $this->workshop_count === 1) {
            $label = 'Workshop for Nurse';
        } else {

            $label = 'Symposium';

            if ($this->workshop_count > 0) {
                $workshopText = $this->workshop_count == 1
                    ? '1 Workshop'
                    : $this->workshop_count . ' Workshops';

                $label .= ' + ' . $workshopText;
            }
        }

        // Bird label (optional safety)
        if ($this->bird_type) {
            $label .= ' (' . strtoupper($this->bird_type) . ' BIRD)';
        }

        return $label;
    }
}
