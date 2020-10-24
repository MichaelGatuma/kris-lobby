<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $created_at
 * @property string $updated_at
 * @property string $ProjectTitle
 * @property int $Project_ID
 * @property string $ProjectAbstract
 * @property int $Researcher_ID
 * @property int $User_ID
 * @property string $ProjectResearchAreas
 * @property string $ResearchersInvolved
 * @property boolean $Funded
 * @property int $Funder_ID
 * @property string $Status
 * @property string $abstractDocumentPath
 * @property string $otherProjectDocsPath
 * @property Researcher $researcher
 * @property User $user
 */
class Project extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'researchprojects';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Project_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'ProjectTitle', 'ProjectAbstract', 'Researcher_ID', 'User_ID', 'ProjectResearchAreas', 'ResearchersInvolved', 'Funded', 'Funder_ID', 'Status', 'abstractDocumentPath', 'otherProjectDocsPath'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function researcher()
    {
        return $this->belongsTo('App\Models\Researcher', 'Researcher_ID', 'Researcher_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'User_ID');
    }
}
