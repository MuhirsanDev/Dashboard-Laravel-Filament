<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\View\View;

class Form extends Component implements HasForms
{
    use InteractsWithForms;

    public $name;
    public $email;
    public $password;
    public $passwordConfirmation;
    public $birth;
    public $gender;
    public $address;
    public $biodata;

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Authentication')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Card::make()->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(100)
                                ->columnSpan(2),

                            TextInput::make('email')
                                ->email()
                                ->label('Email Address')
                                ->required()
                                ->maxLength(100)
                                ->columnSpan(2),

                            TextInput::make('password')
                                ->password()
                                ->required()
                                ->minLength(8)
                                ->same('passwordConfirmation')
                                ->dehydrated(fn ($state) => filled($state))
                                ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                            TextInput::make('passwordConfirmation')
                                ->password()
                                ->label('Password Confirmation')
                                ->required()
                                ->minLength(8)
                                ->dehydrated(false),
                        ])->columns(2)
                    ]),

                Wizard\Step::make('Profile')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('birth')
                            ->type('date')
                            ->rules('date'),

                        Select::make('gender')
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                            ]),

                        Textarea::make('address')
                            ->columnSpan(2)
                            ->rows(5)
                    ])->columns(2),

                Wizard\Step::make('Biodata')
                    ->icon('heroicon-o-pencil')
                    ->schema([
                        Textarea::make('biodata')
                            ->rows(8)
                    ])
            ])
                ->submitAction(new HtmlString('<button type="submit" style="background-color:blue" class="text-white w-20 rounded-lg p-1">Save</button>'))
        ];
    }

    public function render()
    {
        return view('livewire.form');
    }

    public function submit(): void
    {
        $input = $this->form->getState();
        User::create($input);
        redirect()->to('/completed');
    }

    public function completed(): View
    {
        return view('livewire.completed');
    }
}
