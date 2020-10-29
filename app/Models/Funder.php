<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Funder_ID
 * @property string $created_at
 * @property string $updated_at
 * @property string $FunderName
 * @property string $FunderWebsite
 * @property string $FunderphysicalAddress
 * @property string $FunderPostalAddress
 * @property Funder $funder
 * @property FundingOpportunity[] $fundingopportunities
 */
class Funder extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Funder_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'FunderName', 'FunderWebsite', 'FunderphysicalAddress', 'FunderPostalAddress'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function funder()
    {
        return $this->belongsTo('App\Models\Funder', 'Funder_ID', 'Funder_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fundingopportunities()
    {
        return $this->hasMany('App\Models\FundingOpportunity', 'Funder_ID', 'Funder_ID');
    }
}
