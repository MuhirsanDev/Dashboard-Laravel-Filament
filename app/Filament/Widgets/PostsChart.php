<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Post;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class PostsChart extends ChartWidget
{
    protected static ?string $heading = 'Posts Chart';

    protected function getData(): array
    {
        $data = Trend::model(Post::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) {
                $date = Carbon::createFromFormat('Y-m', $value->date);
                $formatedDate = $date->format('M');

                return $formatedDate;
            }),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
