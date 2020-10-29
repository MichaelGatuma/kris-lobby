<?php

namespace App\Http\Livewire\Forms;

use App\Models\Department;
use App\Models\Researcher;
use App\Models\Researchinstitution;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Tanthammar\TallForms\FileUpload;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\Radio;
use Tanthammar\TallForms\Select;
use Tanthammar\TallForms\TallForm;
use Tanthammar\TallForms\Textarea;
use Tanthammar\TallForms\Traits\FillsColumns;
use Tanthammar\TallForms\Traits\UploadsFiles;
use function PHPUnit\Framework\isEmpty;

class createResearcher extends Component
{
    use TallForm, WithFileUploads, UploadsFiles, FillsColumns;
    public $CV;
    public $users = [];
    public $user_id;

    public function mount(?Researcher $researcher)
    {
        $this->users = User::all()->sortBy('id')->pluck('id', 'name')->toArray();

        //Gate::authorize()
        $this->fill([
            'formTitle' => trans('global.create') . ' ' . trans('crud.researcher.title_singular'),
            'wrapWithView' => false, //see https://github.com/tanthammar/tall-forms/wiki/installation/Wrapper-Layout
            'showGoBack' => false,
            'showReset' => false,
        ]);
        $this->mount_form($researcher); // $researcher from hereon, called $this->model
    }

    // Mandatory method
    public function onCreateModel($validated_data)
    {
        // Set the $model property in order to conditionally display fields when the model instance exists, on saveAndStayResponse()
        $this->model = Researcher::create($validated_data);
    }

    // OPTIONAL method used for the "Save and stay" button, this method already exists in the TallForm trait
    public function onUpdateModel($validated_data)
    {
        $this->model->update($validated_data);
    }

    public function updatedUser($validated_value)
    {
        $this->user_id = $this->form_data['user'];
        if ($this->user_id > 0 && !is_null(User::find($this->user_id)->researcher)) {
            $researcher = User::find($this->user_id)->researcher;
            $this->form_data['Gender'] = $researcher->Gender;
            $this->form_data['PhoneNumber'] = $researcher->PhoneNumber;
            $this->form_data['DOB'] = $researcher->DOB;
            $this->form_data['ResearchAreaOfInterest'] = $researcher->ResearchAreaOfInterest;
            $this->form_data['DepartmentID'] = $researcher->DepartmentID;
            $this->form_data['ResearchInstitutionID'] = $researcher->ResearchInstitutionID;
            $this->form_data['Affiliation'] = $researcher->Affiliation;
            $this->form_data['AboutResearcher'] = $researcher->AboutResearcher;
            $this->form_data['Approved'] = $researcher->Approved;
        } elseif ($this->user_id > 0 && is_null(User::find($this->user_id)->researcher)) {

        } else {
            $this->resetFormData();
        }
    }
    public function updatedDepartmentID($validated_value)
    {
        $this->form_data['Affiliation']=Department::where('Department_ID','=',$this->form_data['DepartmentID'])->pluck('DptName')->first().' - '.Researchinstitution::where('ResearchInstitution_ID','=',$this->form_data['ResearchInstitutionID'])->pluck('RIName')->first();
    }

    public function updatedResearchInstitutionID($validated_value)
    {
        $this->form_data['Affiliation']=Department::where('Department_ID','=',$this->form_data['DepartmentID'])->pluck('DptName')->first().' - '.Researchinstitution::where('ResearchInstitution_ID','=',$this->form_data['ResearchInstitutionID'])->pluck('RIName')->first();
    }

    public function getResearchAreas()
    {
        $researchareas = DB::table('researchareas_ktbl')->pluck('ResearchAreaName');
        return collect($researchareas)->values()->all();
    }

    public function fields()
    {
        return [
            Select::make('Select Existing User', 'user')
                ->options($this->users)
                ->placeholder('Select user')
                ->rules(['required'])
                ->wire('wire:model')
                ->errorMsg('You must select a user'),
            Select::make('Gender', 'Gender')
                ->options(['Male', 'Female'])
                ->placeholder('Male or female?')
                ->rules(['required'])
                ->errorMsg('You must specify gender'),
            Input::make('Date of Birth', 'DOB')
                ->placeholder('1999-06-22')
                ->type('date')
                ->step(7)
                ->min('1900-01-01')
                ->max(now()->format('Y-m-d'))
                ->default('1990-01-01')//important; you must cast existing model date to the correct html5 date input format
                ->rules('required|date_format:"Y-m-d"'),
            Input::make('Phone Number', 'PhoneNumber')
                ->type('tel')
                ->rules(['required', 'numeric'])
                ->inputAttr(['pattern' => "[0-9]{12}", 'maxlength' => 12])
                ->placeholder('i.e 0712345678'),
            Select::make('Research Area', 'ResearchAreaOfInterest')
                ->options($this->getResearchAreas())
                ->placeholder('Select research area?')
                ->rules(['required'])
                ->errorMsg('You must specify research area'),
            Select::make('Research Institution', 'ResearchInstitutionID')
                ->options(Researchinstitution::all()
                    ->pluck('ResearchInstitution_ID', 'RIName')->toArray())
                ->placeholder('Specify Institution?')
                ->rules(['required'])
                ->errorMsg('You must specify institution'),
            Select::make('Department', 'DepartmentID')
                ->options(Department::all()
                    ->pluck('Department_ID', 'DptName')->toArray())
                ->placeholder('Department?')
                ->rules(['required'])
                ->errorMsg('You must specify department'),
            Input::make('Affiliation', 'Affiliation')->inputAttr(['disabled'=>true])
                ->rules(['required']),
//            Select::make('Affiliation', 'Affiliation')
//                ->options([])
//                ->placeholder('Select affiliation?')
//                ->rules(['required'])
//                ->errorMsg('You must specify affiliation')
//                ->help('What should go in affiliations dropdown???'),
            Radio::make('Approved?', 'Approved')
                ->options(['Yes' => 1, 'No' => 0])
                ->default(1),
            Textarea::make('About researcher?', 'AboutResearcher')
                ->placeholder('Say Something')
                ->rows(4)
                ->required()
                ->errorMsg('Add an about info')
                ->rules('required|string'),
            $this->user_id > 0 ? FileUpload::make('Upload CV', 'CV')
                ->help('Max 1024kb, png, jpeg, gif or tiff') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:png,jpg,jpeg,gif,tiff|max:1024') //only if you want to override livewire main config validation
                ->accept("pdf/*") : null,
        ];
    }
}
