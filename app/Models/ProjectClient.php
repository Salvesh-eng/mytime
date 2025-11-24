<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectClient extends Model
{
    protected $fillable = [
        'project_id',
        'client_name',
        'contact_person',
        'email',
        'phone',
        'company_name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'billing_email',
        'billing_address',
        'tax_id',
        'billing_notes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function getContactDetailsAttribute()
    {
        return [
            'name' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company_name,
        ];
    }

    public function getBillingDetailsAttribute()
    {
        return [
            'email' => $this->billing_email,
            'address' => $this->billing_address,
            'tax_id' => $this->tax_id,
            'notes' => $this->billing_notes,
        ];
    }
}
