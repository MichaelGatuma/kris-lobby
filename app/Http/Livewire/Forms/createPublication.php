<?php

namespace App\Http\Livewire\Forms;

use App\Models\Publication;
use App\Models\Researcher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Tanthammar\TallForms\FileUpload;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\Select;
use Tanthammar\TallForms\TallForm;
use Tanthammar\TallForms\Textarea;
use Tanthammar\TallForms\Traits\UploadsFiles;

class createPublication extends Component
{
    use TallForm, WithFileUploads, UploadsFiles;
    public $PublicationPath;
    public $UserID;

    public function mount(?Publication $publication)
    {
        $publication = Publication::first();
//        $this->model->User_ID=Researcher::find($this->form_data['Researcher_ID'])->User_ID;
        //Gate::authorize()
        $this->fill([
            'formTitle' => trans('global.create') . ' ' . trans('crud.publication.title_singular'),
            'wrapWithView' => false, //see https://github.com/tanthammar/tall-forms/wiki/installation/Wrapper-Layout
            'showGoBack' => false,
            'showReset' => false,
        ]);
        $this->mount_form($publication); // $publication from hereon, called $this->model
    }


    // Mandatory method
    public function onCreateModel($validated_data)
    {
        // Set the $model property in order to conditionally display fields when the model instance exists, on saveAndStayResponse()
        $this->model->User_ID = Researcher::find($validated_data['Researcher_ID'])->User_ID;
        $this->model = Publication::create($validated_data);
    }

    // OPTIONAL method used for the "Save and stay" button, this method already exists in the TallForm trait
    public function onUpdateModel($validated_data)
    {
        $this->model->update($validated_data);
    }

    public function savePublicationPath($validated_file)
    {
        $path = filled($validated_file) ? $this->PublicationPath->store('PublicationLists') : null;
        //do something with the model?
        if (optional($this->model)->exists && filled($path)) {
            $this->model->PublicationPath = $path;
            $this->model->save();
        }
    }

    public function updatedResearcherID($validated_value)
    {
        $this->model = new Publication();
        if (Publication::where('Researcher_ID', '=', $validated_value)->first() != null) {
            $this->model = Publication::where('Researcher_ID', '=', $validated_value)->first();
        } else {
            $this->model = new Publication();
        }
        $this->model->User_ID = Researcher::find($validated_value)->User_ID;
        $this->model->Researcher_ID = $validated_value;
        $this->mount_form($this->model);
    }

    public function fields()
    {
        return [
            Select::make('Researcher', 'Researcher_ID')
                ->options($this->getResearchers())
                ->rules('required'),
            Input::make('Publication Title', 'PublicationTitle')
                ->rules('required'),
            optional($this->model)->exists //you probably do not want to attach files if the model does not exist
                ? FileUpload::make('Upload Publication', 'PublicationPath')
                ->help('Max 5 megabytes, *.pdf (Existing Publication files will be replaced)') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:pdf|max:5120') //only if you want to override livewire main config validation
                ->accept(".pdf") : null,
            Input::make('Date of Publication', 'DateOfPublication')
                ->type('date')
                ->step(7)
                ->min('1900-01-01')
                ->max(now()->format('Y-m-d'))
                ->default('2001-01-01')//important; you must cast existing model date to the correct html5 date input format
                ->rules('required|date_format:"Y-m-d"'),
            Textarea::make('Collaborators', 'Collaborators')
                ->rows(3)
                ->placeholder('')
                ->rules('string'),
            Input::make('Publication url', 'PublicationURL')
                ->type('url')
                ->prefix('https://')
                ->rules('active_url'),
        ];
    }

    public function getResearchers()
    {
        return collect(DB::table('researchers')->leftJoin('users', 'researchers.User_ID', 'users.id')->get())->pluck('Researcher_ID', 'name')->all();
    }
}
