<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                TextInput::make('brand'),
                TextInput::make('category'),
                RichEditor::make('description'),
                TextInput::make('price')
                    ->prefix('$'),
                TextInput::make('rating')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Image')
                    ->rounded(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),

                TextColumn::make('brand')
                    ->searchable()
                    ->sortable()
                    ->color('secondary')
                    ->alignLeft(),

                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                BadgeColumn::make('price')
                    ->searchable()
                    ->sortable()
                    ->colors(['secondary'])
                    ->prefix('$'),

                BadgeColumn::make('rating')
                    ->colors([
                        'danger' => static fn ($state): bool => $state <= 3,
                        'warning' => static fn ($state): bool => $state > 3 && $state <= 4.5,
                        'success' => static fn ($state): bool => $state > 4.5,
                    ])
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
