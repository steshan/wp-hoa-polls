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
    document.getElementById('questions_title').style.border = '';
}

function removeElement(elementId) {
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}

function addPollQuestion() {
    questionId++;
    var html = '<input type="text" onchange="resetErrors(\'question-' + questionId + '\');" id="question-' + questionId + '" name="poll_questions[]"><a href="" onclick="removeElement(\'question-entry-' + questionId + '\'); return false;">Remove</a>';
    addElement('poll_questions', 'p', 'question-entry-' + questionId, html);
}

var questionId = 0;

function confirmDelete() {
    return confirm("Вы подтверждаете удаление?");
}

function confirmReadOnly() {
    return confirm("Запретить редактировать данные?");
}


function validateAnswerAdd() {
    var result = true;
    var style_error = '2px solid red';
    var roomNumber = document.getElementById('hoa_room_number');
    var answersParent = document.getElementById('hoaAnswerAdd');
    var answers = answersParent.getElementsByTagName('input');
    var numberOfCheckedRadios = 0;

    if (!(isNumeric(roomNumber.value) && roomNumber.value <= 239 && roomNumber.value >= 1)) {
        roomNumber.style.border = style_error;
        result = false;
    }

    for (var i = 0; i < answers.length; i++) {
        if (answers[i].checked) {
            numberOfCheckedRadios += 1;
        }
    }

    if (answers.length / 3 !== numberOfCheckedRadios) {
        answersParent.style.border = style_error;
        result = false;
    }

    return result;
}

function validatePollAdd() {
    var result = true;
    var style_error = '2px solid red';
    var pollQuestions = document.getElementById('poll_questions');

    result = validatePollEdit();

    if (pollQuestions.childElementCount == 0){
        result = false;
        document.getElementById('questions_title').style.border = style_error;
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

function resetErrors(elementId){
    var element = document.getElementById(elementId);
    element.style.border = '';
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}