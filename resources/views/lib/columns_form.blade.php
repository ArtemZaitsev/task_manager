<script>
    function saveFields() {
        var form = $('#display-fields-form');
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(), // serializes the form's elements.
            success: function (data) {
                document.location.reload();
            },
            error: function (data) {
                console.log(data); // show response from the php script.
            },
        });
    }

    $(function () {
        dialog = $("#display-fields").dialog({
            autoOpen: false,
            height: 400,
            width: 350,
            modal: true,
            buttons: {
                "Сохранить": saveFields,
                "Отменить": function () {
                    dialog.dialog("close");
                }
            },
            close: function () {

            }
        });


        $("#show-display-fields").button().on("click", function () {
            dialog.dialog("open");
        });

        $("#fields_select-all").on("click", function () {
            $('#display-fields-form input').prop('checked', true);
        })

    });

</script>


<button class="btn btn-outline-info m-3" id="show-display-fields">Настроить столбцы</button>
<div id="display-fields" style="display: none">


    <button id="fields_select-all">Выбрать все</button>
    <form id="display-fields-form"
          action="{{ $url }}">
        @csrf
        <table>
            @foreach($fields as $field)
                <tr>
                    <td>{{ $field->getLabel() }}</td>
                    <td>
                        <input type="checkbox" name="fields[{{ $field->getName() }}]"
                               @if($field->isNeedDisplay()) checked @endif>
                    </td>
                </tr>
            @endforeach
        </table>
    </form>
</div>
</div>
