/**
 * Created by Siebe on 22/04/2016.
 */

jQuery.noConflict();
(function ($) {
    $(function () {
        /* ----------------------------------------------Handle picture---------------------------------------------- */
        /**
         * Function to make image in create form draggable
         */
        var makeImageDraggable = function () {
            var $image = $("form[name=create] .upload #imagePlaceholder img");
            $image.show();
            $("form[name=create] .upload #imagePlaceholder label").hide();
            var widthImage = $image[0].clientWidth;
            var widthPlace = document.getElementById("imagePlaceholder").clientWidth;
            var leftOfHolder = $("form[name=create] #imagePlaceholder").offset().left;
            console.log(leftOfHolder);
            var containWidth = widthImage - widthPlace;
            console.log(containWidth);
            $image.draggable({
                containment: [leftOfHolder - containWidth + 1, 0, leftOfHolder + 1, 0], //1 px border on each side
                axis: "x",
                scroll: false
            });
        };

        /**
         * Function to get the image of the input file and put it in an img src
         *
         * @param input
         */
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imagePlaceholder').find('img').attr('src', e.target.result);
                    // console.log("loaded");
                    makeImageDraggable(); //Loaded here because other the image isn't loaded when handling width
                };
                // console.log("added");
                reader.readAsDataURL(input.files[0]);
            }
        }

        // When the image input has changed
        $("form[name=create] #image").change(function () {
            readURL(this);
        });

        // Check if image has src
        var imageSrc = $("form[name=create] #imagePlaceholder img").attr("src");
        if(imageSrc != "" && imageSrc !== undefined)
        {
            // Make the image visible and draggable when there is one
            makeImageDraggable();
            // Apply previous offset
            var previousOffset = $("#photoOffset").val();
            $("form[name=create] #imagePlaceholder img").css("left", previousOffset);
            // console.log(imageSrc);
        }

        // When sumbiting the creat project form, get the offset of the picture and put it in the input field
        $("form[name=create]").submit(function(e){
            // Search the image and get the left offset
            var leftOffset = $(this).find("#imagePlaceholder img").css("left");
            // Put the offset in the input field
            $(this).find("#photoOffset").val(leftOffset);
        });
    });
})(jQuery);