<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $ResearchInstitution_ID
 * @property string $created_at
 * @property string $updated_at
 * @property string $RIName
 * @property string $RIWebsite
 * @property string $RIpostalAddress
 * @property Department[] $departments
 * @property Researcher[] $researchers
 */
class Researchinstitution extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'ResearchInstitution_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'RIName', 'RIWebsite', 'RIpostalAddress'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments()
    {
        return $this->hasMany('App\Models\Department', 'ResearchInstitution_ID', 'ResearchInstitution_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researchers()
    {
        return $this->hasMany('App\Models\Researcher', 'ResearchInstitutionID', 'ResearchInstitution_ID');
    }
}
