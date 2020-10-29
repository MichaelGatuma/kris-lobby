<?php

namespace App\Http\Livewire\Forms;

use App\Models\Publication;
use App\Models\Researcher;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Tanthammar\TallForms\FileUpload;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\TallForm;
use Tanthammar\TallForms\Textarea;
use Tanthammar\TallForms\Traits\UploadsFiles;

class createPublication extends Component
{
    use TallForm, WithFileUploads, UploadsFiles;
    public $files;
    public $title;
    public $title2;

    public function mount(?Publication $publication)
    {
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
        $this->model = Publication::create($validated_data);
    }

    // OPTIONAL method used for the "Save and stay" button, this method already exists in the TallForm trait
    public function onUpdateModel($validated_data)
    {
        $this->model->update($validated_data);
    }

    /////////////////////////////////
    public function afterFormProperties()
    {
//        $this->title2=$this->title;
        $this->form_data['title2'] = $this->title;//change the value of the populated form data
        $this->form_data['title2'] = $this->form_data['title'];
    }

    public function updatedTitle($validated_value)
    {
//        todo: this code works
//        $this->form_data['title2'] = $this->form_data['title'];
        $this->form_data['title2'] = User::where('id', '=', $this->form_data['title'])->pluck('name');
        $this->title = $this->form_data['title'];
    }
//    public function render()
//    {
//        $this->title2=$this->title;
//        $this->form_data['title2'] = $this->title;//change the value of the populated form data
//        $this->form_data['title2']=$this->form_data['title'];
//
//        return $this->formView();
//    }
    //////////////////////////////////
    public function fields()
    {
        return [
            Input::make('Publication Title', 'title')
                ->rules('required')
                ->wire('wire:model'),
            FileUpload::make('Upload Publication', 'files')
                ->multiple()
                ->help('Max 1024kb, png, jpeg, gif or tiff') //important for usability to inform about type/size limitations
                ->rules('nullable|mimes:png,jpg,jpeg,gif,tiff|max:1024') //only if you want to override livewire main config validation
                ->accept("pdf/*"),
            Input::make('Date of Publication', 'publication_date')
                ->type('date')
                ->step(7)
                ->min('1900-01-01')
                ->max(now()->format('Y-m-d'))
                ->default('1990-01-01')//important; you must cast existing model date to the correct html5 date input format
                ->rules('required|date_format:"Y-m-d"'),
            $this->title>0 ? Textarea::make('Collaborators', 'title2')
                ->rows(3)
                ->placeholder('')
                ->rules('string')
                ->wire('wire:model'): null,
            Input::make('Publication url')
                ->type('url')
                ->prefix('https://')
                ->rules('active_url'),
        ];
    }
}
