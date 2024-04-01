document.addEventListener('DOMContentLoaded', function() {
    var utmFields = ['utm_source', 'utm_medium', 'utm_term', 'utm_content', 'utm_campaign'];

    utmFields.forEach(function(field) {
        var inputField = document.querySelector('input[name="' + field + '_time"]');
        if (inputField) {

            inputField.addEventListener('keypress', function(event) {
                var keyCode = event.keyCode;
                if (keyCode < 48 || keyCode > 57) {
                    event.preventDefault();
                }
            });

            if (!inputField.value.trim()) {
                inputField.value = '3600';
            }

            var inputValue = inputField.value;
            var timeInSeconds = parseInt(inputValue, 10);

			var days = Math.floor(timeInSeconds / 86400);
            var hours = Math.floor((timeInSeconds % 86400) / 3600);
            var minutes = Math.floor((timeInSeconds % 3600) / 60);
            var seconds = timeInSeconds % 60;

            var span = document.createElement('span');
            span.id = field + '_span';
            span.textContent = ' Час життя цього cookie ' + days + 'дн. ' + hours + 'год. ' + minutes + 'хв. ' + seconds + 'сек.';

            inputField.parentNode.insertBefore(span, inputField.nextSibling);
        }
    });
});