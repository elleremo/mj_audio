var $ = jQuery.noConflict();
(function ($) {

    var mj_audio_player_script = {
        run: function () {
            var this_class = this;
            this.tabs(".podcast-audio-panel-item-coll");
            this.audio_init();
            this.change_tab();
        }
        , audio_init: function () {
            var this_class = this;
            $('audio').audioPlayer({
                classPrefix: 'audioplayer',
                strPlay: 'Play',
                strPause: 'Pause',
                strVolume: 'Volume'
            });

            $(window).on('audio_pause', function () {
                this_class.change_tab()
            });
        }
        , tabs: function (selector) {
            var this_class = this;
            $(selector).on("click", function () {
                var type;
                var container = $(this).closest('.podcast-audio-panel-item');

                if ($(this).hasClass('description')) {
                    type = ".description";
                } else if ($(this).hasClass('play')) {
                    type = ".play";
                    var audio_index = $(this).attr('data-index');
                    audio_index = Number(audio_index);
                    audio_index = audio_index - 1;
                    $(window).trigger('audio_pause', audio_index);
                }
                $(".podcast-audio-panel-item-coll-full").removeClass('active');
                $(this).closest(".podcast-audio-panel-item").find(".podcast-audio-panel-item-coll-full" + type).addClass('active');
                container.find("audio").trigger("play");
            });
        }
        , change_tab: function () {
            var this_class = this;
            $('audio').trigger("pause");
        }

    };

    $(document).ready(function () {
        mj_audio_player_script.run();
    });
})(jQuery);
