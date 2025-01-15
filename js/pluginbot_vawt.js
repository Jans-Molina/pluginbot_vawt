jQuery(document).ready(function ($) {
    $('#vawt_cb-chatbot-send').click(function () {
        const question = $('#vawt_cb-chatbot-input').val();
        if (!question) return;

        $('#vawt_cb-chatbot-messages').append('<div class="vawt_cb-chatbot-user">' + question + '</div>');

        $.post(
            vawt_cb_chatbot_ajax.url,
            { action: 'vawt_cb_chatbot_response', question: question },
            function (response) {
                $('#vawt_cb-chatbot-messages').append('<div class="vawt_cb-chatbot-bot">' + response.response + '</div>');
            }
        );

        $('#vawt_cb-chatbot-input').val('');
    });
});
