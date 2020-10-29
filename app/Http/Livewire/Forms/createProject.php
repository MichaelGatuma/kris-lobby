<?php

namespace App\Http\Livewire\Forms;

use App\Models\Funder;
use App\Models\Researcher;
use App\Models\Researchproject;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Tanthammar\TallForms\FileUpload;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\Radio;
use Tanthammar\TallForms\Select;
use Tanthammar\TallForms\TallForm;
use Tanthammar\TallForms\Textarea;
use Tanthammar\TallForms\Traits\UploadsFiles;

class createProject extends Component
{
    use TallForm, WithFileUploads, UploadsFiles;
    public $abstractDocumentPath, $otherProjectDocsPath;
    public $isFunded;

    public function mount(?Researchproject $researchproject)
    {
        $researchproject=Researchproject::first();
        $this->isFunded = false;
        //Gate::authorize()
        $this->fill([
            'formTitle' => trans('global.create') . ' ' . trans('crud.researchproject.title_singular'),
            'wrapWithView' => false, //see https://github.com/tanthammar/tall-forms/wiki/installation/Wrapper-Layout
            'showGoBack' => false,
            'showReset' => false,
        ]);
        $this->mount_form($researchproject); // $researchproject from hereon, called $this->model
    }


    // Mandatory method
    public function onCreateModel($validated_data)
    {
        // Set the $model property in order to conditionally display fields when the model instance exists, on saveAndStayResponse()
        $this->model = Researchproject::create($validated_data);
    }

    // OPTIONAL method used for the "Save and stay" button, this method already exists in the TallForm trait
    public function onUpdateModel($validated_data)
    {
        $this->model->update($validated_data);
    }
    public function updatedResearcherID($validated_value)
    {
//        $this->model = new Researchproject();
        if (Researchproject::where('Researcher_ID', '=', $validated_value)->first() != null) {
            $this->model = Researchproject::where('Researcher_ID', '=', $validated_value)->first();
        } else {
            $this->model = new Researchproject();
        }
        $this->model->User_ID = Researcher::find($validated_value)->User_ID;
        $this->model->Researcher_ID = $validated_value;
        $this->mount_form($this->model);
    }
    public function saveAbstractDocumentPath($validated_file)
    {
        $path = filled($validated_file) ? $this->abstractDocumentPath->store('ProjectAbstractDocuments') : null;
        //do something with the model?
        if (optional($this->model)->exists && filled($path)) {
            $this->model->abstractDocumentPath = $path;
            $this->model->save();
        }
    }
    public function saveOtherProjectDocsPath($validated_file)
    {
        $path = filled($validated_file) ? $this->otherProjectDocsPath->store('ProjectRelatedDocument.pdf') : null;
        //do something with the model?
        if (optional($this->model)->exists && filled($path)) {
            $this->model->otherProjectDocsPath = $path;
            $this->model->save();
        }
    }
    public function updatedFunded($validated_value)
    {
        $this->isFunded = $validated_value;
    }

    public function fields()
    {
        return [
            Select::make('Researcher','Researcher_ID')
                ->options($this->getResearchers())
                ->rules('required'),
            Input::make('Project Title', 'ProjectTitle')
                ->rules('required'),
            Textarea::make('Project Abstract', 'ProjectAbstract')
                ->rows(8)
                ->rules('string'),
            optional($this->model)->exists //you probably do not want to attach files if the model does not exist
                ? FileUpload::make('Upload Abstract file', 'abstractDocumentPath')
                ->help('Max 5 megabytes, *.pdf (Existing Project files will be replaced)') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:pdf|max:5120') //only if you want to override livewire main config validation
                ->accept(".pdf") : null,
            Select::make('Project Research Area', 'ProjectResearchAreas')
                ->options($this->getResearchAreas())
                ->placeholder('Select research area?')
                ->rules(['required'])
                ->errorMsg('You must specify research area'),
//            todo: use tag fields below
            Textarea::make('Researchers Involved', 'ResearchersInvolved')
                ->rows(3)
                ->rules('string'),
            Radio::make('Is project funded?', 'Funded')
                ->options(['Yes' => 1, 'No' => 0])
                ->default(0),
            $this->isFunded ? Select::make('Funder', 'Funder_ID')
                ->options($this->getFunders())
                ->placeholder('Select Funder') : null,
            Select::make('Project Status', 'Status')
                ->options(['Ongoing', 'Completed'])
                ->default('Ongoing'),
            optional($this->model)->exists //you probably do not want to attach files if the model does not exist
                ? FileUpload::make('Upload Other project files', 'otherProjectDocsPath')
                ->multiple()
                ->help('Max 5 megabytes, *.pdf (Existing Project files will be replaced)') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:pdf|max:5120') //only if you want to override livewire main config validation
                ->accept(".pdf") : null,
        ];
    }

    public function getResearchAreas()
    {
        $researchareas = DB::table('researchareas_ktbl')->pluck('ResearchAreaName');
        return collect($researchareas)->values()->all();
    }

    public function getFunders()
    {
        return Funder::all()->pluck(['Funder_ID' => 'FunderName']);
    }
    public function getResearchers()
    {
        return collect(DB::table('researchers')->leftJoin('users', 'researchers.User_ID', 'users.id')->get())->pluck('Researcher_ID', 'name')->all();
    }
}
