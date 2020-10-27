<?php

namespace App\Http\Livewire\Forms;

use App\Models\Department;
use App\Models\Researcher;
use App\Models\Researchinstitution;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\Radio;
use Tanthammar\TallForms\Select;
use Tanthammar\TallForms\TallForm;
use Tanthammar\TallForms\Textarea;

class createResearcher extends Component
{
    use TallForm;

    public function mount(?Researcher $researcher)
    {
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

    public function fields()
    {
        return [
            Select::make('Gender')
                ->options(['Male' => 'Male', 'Female' => 'Female'])
                ->placeholder('Male or female?')
                ->rules(['required'])
                ->errorMsg('You must specify gender'),
            Input::make('Date of Birth')
                ->placeholder('1999-06-22')
                ->type('date')
                ->step(7)
                ->min('1900-01-01')
                ->max(now()->format('Y-m-d'))
                ->default('1990-01-01')//important; you must cast existing model date to the correct html5 date input format
                ->rules('required|date_format:"Y-m-d"'),
            Input::make('Phone Number')
                ->type('tel')
                ->rules(['required', 'numeric'])
                ->inputAttr(['pattern' => "[0-9]{12}", 'maxlength' => 12])
                ->placeholder('i.e 0712345678'),
            Select::make('Research Area')
                ->options($this->getResearchAreas())
                ->placeholder('Select research area?')
                ->rules(['required'])
                ->errorMsg('You must specify gender'),
            Select::make('Research Institution')
                ->options(Researchinstitution::all()->pluck(['ResearchInstitution_ID' => 'RIName']))
                ->placeholder('Specify Institution?')
                ->rules(['required'])
                ->errorMsg('You must specify institution'),
            Select::make('Department')
                ->options(Department::all()->pluck(['Department_ID' => 'DptName']))
                ->placeholder('Department?')
                ->rules(['required'])
                ->errorMsg('You must specify department'),
            Select::make('Affiliation')
                ->options([])
                ->placeholder('Select affiliation?')
                ->rules(['required'])
                ->errorMsg('You must specify affiliation')
                ->help('What should go in affiliations dropdown???'),
            Radio::make('Approved?')
                ->options(['Yes' => 1, 'No' => 0])
                ->default(0),
            Textarea::make('About researcher?')
                ->placeholder('Say something...')
                ->rows(4)
                ->required()
                ->rules('required|string'),

        ];
    }

    public function getResearchAreas()
    {
        $researchareas = DB::table('researchareas_ktbl')->pluck('ResearchAreaName');
        return collect($researchareas)->values()->all();
    }

}
