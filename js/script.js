function toggleVoteResults() {
    var x = document.getElementById('VoteResults');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}

function addElement(parentId, elementTag, elementId, html) {
    var p = document.getElementById(parentId);
    var newElement = document.createElement(elementTag);
    newElement.setAttribute('id', elementId);
    newElement.innerHTML = html;
    p.appendChild(newElement);
}

function removeElement(elementId) {
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}

function addPollQuestion() {
    questionId++;
    var html = '<input type="text" id="question-' + questionId + '" name="poll_questions[]"><a href="" onclick="removeElement(\'question-' + questionId + '\'); return false;">Remove</a>';
    addElement('poll_questions', 'p', 'question-' + questionId, html);
}

var questionId = 0;

function confirmDelete() {
    return confirm("Вы подтверждаете удаление?");
}

function confirmReadOnly() {
    return confirm("Запретить редактировать данные?");
}

function validatePollAdd() {
    var result = true;
    var style_error = '2px solid red';
    var pollQuestions = document.getElementById('poll_questions');

    result = validatePollEdit();

    if (pollQuestions.childElementCount == 0){
        result = false;
    } else {
        var children = pollQuestions.children;
        for (var i = 0; i < children.length; i++) {
            if (children[i].getElementsByTagName('input')[0].value == ''){
                children[i].getElementsByTagName('input')[0].style.border = style_error;
                result = false;
            }
        }

    }

    return result;
}

function validatePollEdit() {
    var result = true;
    var style_error = '2px solid red';
    var pollName = document.getElementById('hoa_poll_name');
    var pollQuorum = document.getElementById('hoa_poll_quorum');

    if (pollName.value === '') {
        pollName.style.border = style_error;
        result = false;
    }

    if (!(isNumeric(pollQuorum.value) && pollQuorum.value <= 100 && pollQuorum.value >=0 )) {
        pollQuorum.style.border = style_error;
        result = false;
    }

    return result;
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}