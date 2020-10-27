<?php

namespace App\Http\Livewire\Forms;

use App\Models\Funder;
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
    use TallForm,WithFileUploads, UploadsFiles;
    public $abstract_files;
    public $other_files;

    public function mount(?Researchproject $researchproject)
    {
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

    public function fields()
    {
        return [
            Input::make('Project Title')
                ->rules('required'),
            Textarea::make('Project Abstract')
                ->rows(4)
                ->rules('string'),
            FileUpload::make('Upload Abstract file', 'abstract_files')
                ->help('Max 1024kb, png, jpeg, gif or tiff') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:png,jpg,jpeg,gif,tiff|max:1024') //only if you want to override livewire main config validation
                ->accept("pdf/*"),
            Select::make('Project Research Area')
                ->options($this->getResearchAreas())
                ->placeholder('Select research area?')
                ->rules(['required'])
                ->errorMsg('You must specify research area'),
            Textarea::make('Researchers Involved')
                ->rows(3)
                ->rules('string'),
            Radio::make('Is project funded?')
                ->options(['Yes' => 1, 'No' => 0])
                ->default(0),
            Select::make('Funder')
                ->options(['$this->getFunders()'])
                ->placeholder('Select Funder'),
            Select::make('Project Status')
                ->options(['Ongoing' => 'Ongoing', 'Completed' => 'Completed'])
                ->default('Ongoing'),
            FileUpload::make('Upload Other project files', 'other_files')
                ->multiple()
                ->help('Max 1024kb, png, jpeg, gif or tiff') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:png,jpg,jpeg,gif,tiff|max:1024') //only if you want to override livewire main config validation
                ->accept("pdf/*"),

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
}
