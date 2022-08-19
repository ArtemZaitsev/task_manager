@foreach($buttons as $button)
    @if($button != null)
        @include(
            $button['template'],
            $button['templateData']
        )
    @endif
@endforeach
