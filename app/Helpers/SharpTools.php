<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class SharpTools
{
    public static function groupByDateRange($collection, $groupBy = null)
    {
        if(!($collection instanceof Collection)) return null;
        
        $today = $collection->filter(function ($item) {
            return Carbon::now()->isSameMonth($item->created_at)
                && Carbon::now()->isSameWeek($item->created_at)
                && Carbon::now()->isSameDay($item->created_at);
            return $item->created_at->isSameMonth(now()) 
                && $item->created_at->isSameWeek(now())
                && $item->created_at->isSameDay(now());
        });
        $week = $collection->filter(function ($item) {
            return Carbon::now()->isSameMonth($item->created_at)
                && Carbon::now()->isSameWeek($item->created_at)
                && !Carbon::now()->isSameDay($item->created_at);
            return $item->created_at->isSameMonth(now()) 
                && $item->created_at->isSameWeek(now())
                && !$item->created_at->isSameDay(now());
        });
        $month = $collection->filter(function ($item) {
            return Carbon::now()->isSameMonth($item->created_at)
                && !Carbon::now()->isSameWeek($item->created_at)
                && !Carbon::now()->isSameDay($item->created_at);
            return $item->created_at->isSameMonth(now()) 
                && !$item->created_at->isSameWeek(now())
                && !$item->created_at->isSameDay(now());
        });
        $even_earlier = $collection->filter(function ($item) {
            return !Carbon::now()->isSameMonth($item->created_at)
                && !Carbon::now()->isSameWeek($item->created_at)
                && !Carbon::now()->isSameDay($item->created_at);
            return !$item->created_at->isSameMonth(now()) 
                && !$item->created_at->isSameWeek(now())
                && !$item->created_at->isSameDay(now());
        });

        if($groupBy) {
            $today          = $today->groupBy($groupBy)->all();
            $week           = $week->groupBy($groupBy)->all();
            $month          = $month->groupBy($groupBy)->all();
            $even_earlier   = $even_earlier->groupBy($groupBy)->all();
        }


        return [
            'today'   => $today,
            'week'    => $week,
            'month'   => $month,
            'even_earlier'   => $even_earlier,
            'all' => $collection,
            'total' => $collection->count(),
        ];
    }
}