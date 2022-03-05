<html>
<head>
    <title>@yield('title')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
            crossorigin="anonymous"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>--}}

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="/css/app.css">
    <script>
        $(document).ready(function () {
            function displayStartEndFilter(modeSelector, filter) {
                let newValue = modeSelector.val();
                switch (newValue) {
                    case 'range':
                        filter.find('input[data-type="start"]').show();
                        filter.find('input[data-type="end"]').show();
                        break;
                    case 'today':
                        filter.find('input[data-type="start"]').hide();
                        filter.find('input[data-type="end"]').hide();
                        break;
                }
            }


            $('.select2').select2();
            let dateFilters = $('div[data-filter="date"]');
            dateFilters.each(function () {
                let filter = $(this);
                let modeSelector = filter.find('select[data-type="mode"]');
                displayStartEndFilter(modeSelector, filter);
                modeSelector.change(function () {
                    displayStartEndFilter(modeSelector, filter);
                });
            })
        });
    </script>
</head>
<body>


@yield('content')

</body>
</html>
