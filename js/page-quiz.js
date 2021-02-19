$(document).ready(function()
{
    $('.ccm-page-quiz').each(function() {
        var $quiz = $(this);
        var $answers_text = $quiz.find('.answer-text');
        var $answers_buttons = $quiz.find('.answer-btn');

        $answers_buttons.click(function(e) {
            e.preventDefault();
            var index = $(this).index();

            $quiz.find('.answers-feedback .answer')
                .removeClass('active')
                .eq(index)
                .addClass('active');

            $answers_buttons.removeClass('correct incorrect');

            if ($(this).data('is-correct')) {
                $answers_text.addClass('inactive')
                    .filter('[data-is-correct=1]')
                    .removeClass('inactive');
                $(this).addClass('correct');
            } else {
                $answers_text.removeClass('inactive');
                $(this).addClass('incorrect');
            }
        });
    });

});