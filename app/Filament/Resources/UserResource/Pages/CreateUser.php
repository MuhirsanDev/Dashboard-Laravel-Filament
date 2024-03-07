<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CreateUser extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = UserResource::class;

    public function getSteps(): array
    {
        return [
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
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')->preload()
                            ->columnSpan(2),
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
        ];
    }
}
