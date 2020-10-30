<?php

namespace App\Http\Livewire\Forms;

use App\Models\User;
use Livewire\Component;
use Tanthammar\TallForms\Input;
use Tanthammar\TallForms\Select;
use Tanthammar\TallForms\TallForm;

class createUser extends Component
{
    use TallForm;

    public function mount(?User $user)
    {
        //Gate::authorize()
        $user=new User();
        $this->fill([
            'formTitle' => trans('global.create') . ' ' . trans('crud.user.title_singular'),
            'wrapWithView' => false, //see https://github.com/tanthammar/tall-forms/wiki/installation/Wrapper-Layout
            'showGoBack' => false,
            'showReset' => false,
        ]);
        $this->mount_form($user); // $user from hereon, called $this->model
    }

    // Mandatory method
    public function onCreateModel($validated_data)
    {
        $this->model=new User();
        $this->model->password= bcrypt("123");
        $this->model->profPic= 'storage/ProfilePictures/' . $validated_data['email'] . '.jpg';
        $this->model->email=$validated_data['email'];
        $this->model->name=$validated_data['name'];
        $this->model->Title=$validated_data['Title'];
        $this->model->save();
    }

    // OPTIONAL method used for the "Save and stay" button, this method already exists in the TallForm trait
    public function onUpdateModel($validated_data)
    {
//        $this->model->update($validated_data);
    }

    public function fields()
    {
        return [
            Select::make('Title', 'Title')
                ->options(['Prof.', 'Dr.', 'Mr.', 'Mrs.', 'Miss.', 'Ms.'])
            ->default('Prof.'),
            Input::make('Name', 'name')
                ->rules('required|string'),
            Input::make('Email', 'email')
                ->rules('required|email'),
//            Input::make('Password', 'password')
//                ->rules('required|string')->inputAttr(['disabled:'=>true]),
        ];
    }
}
