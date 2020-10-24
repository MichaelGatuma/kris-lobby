<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $Publication_ID
 * @property string $created_at
 * @property string $updated_at
 * @property int $UserID
 * @property int $Researcher_ID
 * @property string $PublicationTitle
 * @property string $PublicationPath
 * @property string $DateOfPublication
 * @property string $Collaborators
 * @property string $PublicationURL
 * @property Researcher $researcher
 * @property User $user
 */
class Publication extends Model
{
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'Publication_ID';

    /**
     * @var array
     */
    protected $fillable = ['created_at', 'updated_at', 'UserID', 'Researcher_ID', 'PublicationTitle', 'PublicationPath', 'DateOfPublication', 'Collaborators', 'PublicationURL'];

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
        return $this->belongsTo('App\Models\User', 'UserID');
    }
}
