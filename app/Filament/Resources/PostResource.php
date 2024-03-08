<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\PostResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\PostResource\Widgets\StatsOverview;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\TagsRelationManagerResource\RelationManagers\TagsRelationManager;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', true)->count() < 3 ? 'danger' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
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
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                TextColumn::make('title')->limit('50')->sortable()->searchable(),
                TextColumn::make('category.name'),
                SpatieMediaLibraryImageColumn::make('cover'),
                ToggleColumn::make('status'),
            ])
            ->filters([
                Filter::make('publish')
                    ->query(fn (Builder $query): Builder => $query->where('status', true)),
                Filter::make('draft')
                    ->query(fn (Builder $query): Builder => $query->where('status', false)),
                SelectFilter::make('Category')
                    ->relationship('category', 'name'),

                DateRangeFilter::make('created_at')
                    ->withIndicator(),


                // Filter::make('created_at')
                //     ->form([
                //         DatePicker::make('From'),
                //         DatePicker::make('To'),
                //     ])

                //     ->indicateUsing(function (array $data): array {
                //         $indicators = [];

                //         if ($data['From'] ?? null) {
                //             $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['From'])->toFormattedDateString())
                //                 ->removeField('From');
                //         }

                //         if ($data['To'] ?? null) {
                //             $indicators[] = Indicator::make('Created to ' . Carbon::parse($data['To'])->toFormattedDateString())
                //                 ->removeField('To');
                //         }

                //         return $indicators;
                //     })
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['From'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                //             )
                //             ->when(
                //                 $data['To'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                //      );
                // })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('download image')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn (Post $record) => route('download.image', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TagsRelationManager::class,
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }
}
