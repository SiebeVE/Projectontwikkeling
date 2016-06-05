/**
 * Created by Siebe on 5/06/2016.
 */

(function ( $ ) {
	console.log("done");
	window.onYouTubePlayerAPIReady = function() {
		$(".youtubeVid").each(function () {
			var $currentVid = $(this);
			var $parent = $currentVid.parent();
			var currentId = $currentVid.attr("id");
			var ytPlayer = new YT.Player(currentId, {
				videoId: $currentVid.data("youtubeid"),
				playerVars: {
					showinfo: 0,
					rel: 0,
					wmode: "opaque"
				}
			});
			$parent.fitVids();
		});
	};
})(jQuery);