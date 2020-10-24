<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Department_ID
 * @property string $created_at
 * @property string $updated_at
 * @property string $DptName
 * @property int $ResearchInstitution_ID
 * @property string $DptWebsite
 * @property string $DptPhysicalAddress
 * @property string $DptPostalAddress
 * @property Researchinstitution $researchinstitution
 * @property Researcher[] $researchers
 */
class Department extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Department_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'DptName', 'ResearchInstitution_ID', 'DptWebsite', 'DptPhysicalAddress', 'DptPostalAddress'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function researchinstitution()
    {
        return $this->belongsTo('App\Models\Researchinstitution', 'ResearchInstitution_ID', 'ResearchInstitution_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researchers()
    {
        return $this->hasMany('App\Models\Researcher', 'DepartmentID', 'Department_ID');
    }
}
