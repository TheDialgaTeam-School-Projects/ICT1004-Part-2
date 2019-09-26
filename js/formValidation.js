function updateFormElementStatus(node, isValid, message) {
    node.classList.remove(isValid ? 'is-invalid' : 'is-valid');
    node.classList.add(isValid ? 'is-valid' : 'is-invalid');

    var validationNode = getElementById(node.parentNode, 'validation-' + node.id);

    if (validationNode) {
        validationNode.classList.remove(isValid ? 'invalid-feedback' : 'valid-feedback');
        validationNode.classList.add(isValid ? 'valid-feedback' : 'invalid-feedback');
        validationNode.textContent = message;
    } else {
        var divElement = document.createElement("div");
        divElement.id = 'validation-' + node.id;
        divElement.classList.add(isValid ? 'valid-feedback' : 'invalid-feedback');

        var textElement = document.createTextNode(message);

        divElement.appendChild(textElement);
        node.parentNode.appendChild(divElement);
    }
}

function getElementById(node, elementId) {
    for (var i = 0; i < node.childNodes.length; i++) {
        if (node.childNodes[i].id === elementId)
            return node.childNodes[i];
    }

    return false;
}