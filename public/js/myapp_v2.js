function addTaskLog() {
    var taskLogsTable = document.getElementById('task-logs-tbody');
    var taskLogsThead = document.getElementById('task-logs-thead');
    var rowTemplate = document.getElementById('task-log-template');
    var newRow = rowTemplate.cloneNode(true);

    var allInputs = newRow.querySelectorAll('input,select');
    var lastIdx = taskLogsTable.hasAttribute('data-last-idx') ? taskLogsTable.getAttribute('data-last-idx')
        : 0;
    lastIdx--;
    taskLogsTable.setAttribute('data-last-idx', lastIdx);

    allInputs.forEach((input) => {
        var nameAttr = input.getAttribute('name');
        var newName = nameAttr.replaceAll('__id__', lastIdx);
        input.setAttribute('name', newName);
    });

    taskLogsTable.appendChild(newRow);
    taskLogsThead.style.removeProperty('display');
}

function deleteTaskLog(delLink) {
    var currentRow = delLink.closest('tr');
    currentRow.remove();
}

function copyToClipboard(elem) {
    var textToCopy = elem.getAttribute('data-text');
    if (navigator && navigator.clipboard && navigator.clipboard.writeText) {
        return navigator.clipboard.writeText(textToCopy);
    }

    const el = document.createElement('textarea');
    el.value = textToCopy;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);

    elem.textContent = 'Скопировано';
    // setTimeout("alert('Привет')", 1000);

    // function refreshText(elem) {
    //     elem.textContent = 'Скопировать путь';
    // }

    // setTimeout(elem.textContent = 'Скопировать путь', 3000);
    // setTimeout("elem.textContent = 'Скопировать путь'", 3000);

}




