<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\PostResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected $listeners = ['refresh' => 'refreshForm'];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function refreshForm()
    {
        $this->fillForm();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Card::make()->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name'),
                        TextInput::make('title')
                            ->live()
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))->required(),
                        TextInput::make('slug')->required(),
                        SpatieMediaLibraryFileUpload::make('cover'),
                        RichEditor::make('content'),
                        Toggle::make('status'),
                        Hidden::make('users_id')
                            ->default(auth()->user()->id),
                        TextInput::make('totsl_comment')
                            ->reactive()
                            ->disabled()
                    ])->columnSpan(1),
                    Card::make()->schema([
                        Placeholder::make('created_at')
                            ->content(fn ($record) => $record->created_at->format('d/m/Y, H:m:s')),
                        // Placeholder::make('Author')
                        //     ->content(fn ($record) => $record->users->name)
                    ])
                ])->columns(1)
        ];
    }
}
