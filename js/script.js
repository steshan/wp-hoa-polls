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