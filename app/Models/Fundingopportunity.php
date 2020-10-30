<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $FundingOpportunity_ID
 * @property string $created_at
 * @property string $updated_at
 * @property int $Funder_ID
 * @property string $ResearchAreasFunded
 * @property string $FundingOpportunityURL
 * @property string $DeadlineForApplication
 * @property Funder $funder
 */
class FundingOpportunity extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'FundingOpportunity_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'Funder_ID', 'ResearchAreasFunded', 'FundingOpportunityURL', 'DeadlineForApplication'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function funder()
    {
        return $this->belongsTo('App\Models\Funder', 'Funder_ID', 'Funder_ID');
    }
}
