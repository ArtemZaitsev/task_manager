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
