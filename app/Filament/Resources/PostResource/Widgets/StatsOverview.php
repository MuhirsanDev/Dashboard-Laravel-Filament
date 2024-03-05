<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('All Post', Post::all()->count())
                ->description('All Post')
                ->descriptionIcon(''),
            Stat::make('Publish', Post::where('status', true)->count())
                ->description('Publish')
                ->descriptionIcon(''),
            Stat::make('Draft', Post::where('status', false)->count())
                ->description('Draft')
                ->descriptionIcon(''),
        ];
    }
}
