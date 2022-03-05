<a style="text-decoration:none" href="{{ App\Utils\UrlUtils::sortUrl(\App\Http\Controllers\Task\TaskController::ACTION_LIST,$sortColumn, request())  }}">{{ $label }}
    @if (request()->has('sort') && request()->get('sort') === $sortColumn)
        @if (request()->get('sort_direction') === 'ASC')
            <b style='font-size: 30px; color: green; font-weight: bold;'>&#8593;</b>
        @else
            <b style='font-size: 30px; color: green; font-weight: bold;'>&#8595;</b>
        @endif
    @endif
</a>
