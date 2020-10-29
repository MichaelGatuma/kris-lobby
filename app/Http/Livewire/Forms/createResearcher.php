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

class createResearcher extends Component
{
    use TallForm, WithFileUploads, UploadsFiles, FillsColumns;
    public $CV;
    public $users = [];

    public function mount(?Researcher $researcher)
    {
        $this->users = collect(User::all()->pluck('id', 'name',))->sort();

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

    public function updatedUserID($validated_value)
    {
        if ($this->form_data['User_ID'] > 0) {
            $this->mount_form(User::find($validated_value)->researcher);
            if (is_null(User::find($validated_value)->researcher)) {
                $this->form_data['User_ID'] = $validated_value;
            }

        } else {
            $this->resetFormData();
        }
    }

    public function updatedDepartmentID($validated_value)
    {
        $this->form_data['Affiliation'] = Department::where('Department_ID', '=', $this->form_data['DepartmentID'])->pluck('DptName')->first() . ' - ' . Researchinstitution::where('ResearchInstitution_ID', '=', $this->form_data['ResearchInstitutionID'])->pluck('RIName')->first();
    }

    public function updatedResearchInstitutionID($validated_value)
    {
        $this->form_data['Affiliation'] = Department::where('Department_ID', '=', $this->form_data['DepartmentID'])->pluck('DptName')->first() . ' - ' . Researchinstitution::where('ResearchInstitution_ID', '=', $this->form_data['ResearchInstitutionID'])->pluck('RIName')->first();
    }

    public function saveCV($validated_file)
    {
        $path = filled($validated_file) ? $this->CV->store('CVs') : null;
        //do something with the model?
        if (optional($this->model)->exists && filled($path)) {
            $this->model->CV = $path;
            $this->model->save();
        }
    }

    public function fields()
    {
        return [
            Select::make('Select Existing User', 'User_ID')
                ->options($this->users)
                ->placeholder('Select user')
                ->rules(['required'])
                ->wire('wire:model')
                ->errorMsg('You must select a user'),
            Select::make('Gender', 'Gender')
                ->options(['Male' => 'Male', 'Female' => 'Female'])
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
                ->wire('wire:model')
                ->placeholder('i.e 0712345678'),
            Select::make('Research Area', 'ResearchAreaOfInterest')
                ->options($this->getResearchAreas())
                ->placeholder('Select research area?')
                ->rules(['required'])
                ->errorMsg('You must specify research area'),
            Select::make('Research Institution', 'ResearchInstitutionID')
                ->options($this->getInstitutions())
                ->placeholder('Specify Institution?')
                ->rules(['required'])
                ->errorMsg('You must specify institution'),
            Select::make('Department', 'DepartmentID')
                ->options($this->getDepartments())
                ->placeholder('Department?')
                ->rules(['required'])
                ->errorMsg('You must specify department'),
            Input::make('Affiliation', 'Affiliation')->inputAttr(['disabled' => true])->help('This field is filled automatically'),
            Radio::make('Approved?', 'Approved')
                ->options(['Yes' => 1, 'No' => 0])
                ->default(1),
            Textarea::make('About researcher?', 'AboutResearcher')
                ->placeholder('Say Something')
                ->rows(4)
                ->required()
                ->errorMsg('Add an about info')
                ->rules('required|string'),
            optional($this->model)->exists //you probably do not want to attach files if the model does not exist
                ? FileUpload::make('Upload CV', 'CV')
                ->help('Max 5 megabytes, *.pdf (Existing CV files will be replaced)') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:pdf|max:5120') //only if you want to override livewire main config validation
                ->accept(".pdf") : null,
        ];
    }

    public
    function getResearchAreas()
    {
        $researchareas = DB::table('researchareas_ktbl')->pluck('ResearchAreaName');
        return collect($researchareas)->values()->all();
    }

    public
    function getInstitutions()
    {
        $institutions = Researchinstitution::all()->pluck('ResearchInstitution_ID', 'RIName',)->toArray();
        return collect($institutions);
    }

    public
    function getDepartments()
    {
        $departments = Department::all()->pluck('Department_ID', 'DptName',);
        return collect($departments);
    }
}
