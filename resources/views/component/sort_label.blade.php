@if(request()->has('sort') && request()->get('sort') === $field)
    @if(request()->get('sort_direction') === 'ASC')
    <b style='color: green; font-weight: bold;'>
        <svg width='24' height='24' fill='green' class='bi bi-arrow-bar-up' viewBox='0 0 16 16'>
            <path fill-rule='evenodd'
                  d='M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z'/>
        </svg>
    </b>
    @else
        <b style='color: green; font-weight: bold;'>
            <svg width='24' height='24' fill='green' class='bi bi-arrow-bar-down' viewBox='0 0 16 16'>
                <path fill-rule='evenodd'
                      d='M1 3.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5zM8 6a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 .708-.708L7.5 12.293V6.5A.5.5 0 0 1 8 6z'/>
            </svg>
        </b>
    @endif
@endif
