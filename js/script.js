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
	    if (confirm("Запретить редактировать данные?")) {
	        return true;
	    } else {
	        return false;
	    }
	}

function validationForm(){
    var pollName = document.getElementById('hoa_poll_name').value;
    var pollQuorum = document.getElementById('hoa_poll_quorum').value;
    if (pollName == ''){
        alert('input poll name');
        return false;
    }
    if (!(isNumeric(pollQuorum) && pollQuorum<=100 && pollQuorum>=0)){
        alert ('incorrect input quorum')
        return false;
    }
    var pollQuestions = document.getElementById('poll_questions');
    if (pollQuestions.childElementCount == 0){
        alert ('create at least one question');
        return false;
    } else {
        var children = pollQuestions.children;
        for (var i = 0; i < children.length; i++) {
            if (children[i].getElementsByTagName('input')[0].value == ''){
                alert ('input question text');
                return false;
            }
        }

    }
    return true;
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}