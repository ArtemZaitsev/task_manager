<html>
<head>
    <title>@yield('title')</title>
    <script src="/js/jquery_3.6.0.js"></script>
    <link rel="stylesheet" href="/css/bootstrap_5.1.3.css">

    <script src="/js/bootstrap_bundle_min.js"></script>
        <link href="/css/select2.css" rel="stylesheet"/>
    <script src="/js/select2.js"></script>
    <script src="/js/myapp_v1.js"></script>
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
