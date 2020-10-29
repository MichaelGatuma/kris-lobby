<?php

namespace App\Http\Livewire\Forms;

use App\Models\Funder;
use App\Models\FundingOpportunity;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\Select;
use Tanthammar\TallForms\TallForm;

class createFundingOpportunity extends Component
{
    use TallForm;

    public function mount(?FundingOpportunity $fundingopportunity)
    {
        //Gate::authorize()
        $this->fill([
            'formTitle' => trans('global.create') . ' ' . trans('crud.fundingopportunity.title_singular'),
            'wrapWithView' => false, //see https://github.com/tanthammar/tall-forms/wiki/installation/Wrapper-Layout
            'showGoBack' => false,
            'showReset' => false,
            'showSave'=>false,
        ]);
        $this->mount_form($fundingopportunity); // $fundingopportunity from hereon, called $this->model
    }


    // Mandatory method
    public function onCreateModel($validated_data)
    {
        // Set the $model property in order to conditionally display fields when the model instance exists, on saveAndStayResponse()
        $this->model = FundingOpportunity::create($validated_data);
    }

    // OPTIONAL method used for the "Save and stay" button, this method already exists in the TallForm trait
    public function onUpdateModel($validated_data)
    {
        $this->model->update($validated_data);
    }

    public function updatedFunderID($validated_value)
    {
//        $this->model = FundingOpportunity::where('Funder_ID', '=', $validated_value);
//        $this->mount_form($this->model);
    }

    public function fields()
    {
        return [
            Select::make('Funder', 'Funder_ID')
                ->options($this->getFunders())
                ->rules('required'),
            Select::make('Research area Funded', 'ResearchAreasFunded')
                ->options($this->getResearchAreas())->wire('wire:model'),
            Input::make('Funding Opportunity url', 'FundingOpportunityURL')
                ->type('url')
                ->prefix('https://')
                ->rules('url'),
            Input::make('Application Deadline', 'DeadlineForApplication')
                ->type('date')
                ->step(7)
                ->min(now()->format('Y-m-d'))
                ->default(now()->format('Y-m-d'))//important; you must cast existing model date to the correct html5 date input format
                ->rules('required|date_format:"Y-m-d"'),
        ];
    }

    public function getFunders()
    {
        return Funder::all()->pluck('Funder_ID', 'FunderName');
    }

    public function getResearchAreas()
    {
        $researchareas = DB::table('researchareas_ktbl')->pluck('ResearchAreaName');
        return collect($researchareas)->values()->all();
    }
}
