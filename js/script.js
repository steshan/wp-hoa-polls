function toggleVoteResults(elementId) {
    var x = document.getElementById(elementId);
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

    resetErrors('questions_title');
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


function validateAnswerAdd(rooms) {
    var result = true;
    var roomNumber = document.getElementById('hoa_room_number');
    var answersParent = document.getElementById('hoaAnswerAdd');
    var answers = answersParent.getElementsByTagName('input');
    var numberOfCheckedRadios = 0;

    if (!(isNumeric(roomNumber.value) && roomNumber.value <= rooms && roomNumber.value >= 1)) {
        setErrors('hoa_room_number');
        result = false;
    }

    for (var i = 0; i < answers.length; i++) {
        if (answers[i].checked) {
            numberOfCheckedRadios += 1;
        }
    }

    if (answers.length / 3 !== numberOfCheckedRadios) {
        setErrors('hoaAnswerAdd');
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
        setErrors('questions_title');
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
    var pollName = document.getElementById('hoa_poll_name');
    var pollQuorum = document.getElementById('hoa_poll_quorum');
    if (pollName.value === '') {
        setErrors('hoa_poll_name');
        result = false;
    }

    if (!(isNumeric(pollQuorum.value) && pollQuorum.value <= 100 && pollQuorum.value >=0 )) {
        setErrors('hoa_poll_quorum');
        result = false;
    }

    return result;
}

function resetErrors(elementId){
    var element = document.getElementById(elementId);
    element.style.border = '';
    var elementMsg = document.getElementById(elementId+'_msg');
    elementMsg.style.display = 'none';
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function setErrors(elementId) {
    var style_error = '2px solid red';
    var element = document.getElementById(elementId);
    element.style.border = style_error;
    var msg = document.getElementById(elementId + '_msg');
    msg.style.display = '';

}