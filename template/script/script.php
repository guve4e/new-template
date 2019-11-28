<?php

?>

<script src="https://www.google.com/recaptcha/api.js?render=6LcfYr8UAAAAAEwVxw7-qNLJP4fTrNK89mjxjUn_"></script>
<script>
    grecaptcha.ready(function() {
        // do request for recaptcha token
        // response is promise with passed token
        grecaptcha.execute('6LcfYr8UAAAAAEwVxw7-qNLJP4fTrNK89mjxjUn_', {action:'validate_captcha'})
            .then(function(token) {
                // add token value to form

                document.getElementById('g-recaptcha-response').value = token;
            });
    });
</script>
