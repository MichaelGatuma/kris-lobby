<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Researcher_ID
 * @property string $created_at
 * @property string $updated_at
 * @property int $User_ID
 * @property string $Gender
 * @property string $DOB
 * @property string $PhoneNumber
 * @property string $ResearchAreaOfInterest
 * @property int $DepartmentID
 * @property int $ResearchInstitutionID
 * @property string $Affiliation
 * @property string $AboutResearcher
 * @property boolean $Approved
 * @property string $CV
 * @property Department $department
 * @property Researchinstitution $researchinstitution
 * @property User $user
 * @property Post[] $posts
 * @property Publication[] $publications
 * @property Researchproject[] $researchprojects
 */
class Researcher extends Model
{
    protected $table='researchers';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Researcher_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'User_ID', 'Gender', 'DOB', 'PhoneNumber', 'ResearchAreaOfInterest', 'DepartmentID', 'ResearchInstitutionID', 'Affiliation', 'AboutResearcher', 'Approved', 'CV'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'DepartmentID', 'Department_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function researchinstitution()
    {
        return $this->belongsTo('App\Models\Researchinstitution', 'ResearchInstitutionID', 'ResearchInstitution_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'User_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'Researcher_ID', 'Researcher_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications()
    {
        return $this->hasMany('App\Models\Publication', 'Researcher_ID', 'Researcher_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function researchprojects()
    {
        return $this->hasMany('App\Models\Researchproject', 'Researcher_ID', 'Researcher_ID');
    }
}
