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
            // console.log(leftOfHolder);
            var containWidth = widthImage - widthPlace;
            // console.log(containWidth);
            $image.draggable({
                containment: [leftOfHolder - containWidth + 1, 0, leftOfHolder + 1, 0], //1 px border on each side
                axis: "x",
                scroll: false
            });
            // console.log("done2");
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
                    // console.log("done1");
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
        if (imageSrc != "" && imageSrc !== undefined) {
            // Make the image visible and draggable when there is one
            makeImageDraggable();
            // console.log("done");
            // Apply previous offset
            var previousOffset = $("#photoOffset").val();
            $("form[name=create] #imagePlaceholder img").css("left", previousOffset);
            // console.log(imageSrc);
        }

        /* -----------------------------------------------Handle phase----------------------------------------------- */
        $("#phases").on("keyup", ".phaseTrigger", function () {
            if ($(this).val() != "") {
                // Get number of this input field
                var triggerPhaseNumber = $(this).attr('id').split("-").pop();
                // Get the number of the last phase
                var lastPhase = $("#numberOfPhases").val();

                // Check if there has to be a new phase field
                if (lastPhase == triggerPhaseNumber) {
                    // Hooray, a new field!
                    // Increase number of hidden input field
                    $("#numberOfPhases").val(parseInt(lastPhase) + 1);
                    var newPhase = $("#numberOfPhases").val();
                    // Get the first phase and clone in new var
                    var $phaseTemplate = $("#phases").find('.phase').first().clone();
                    // Change the id's, for's and name's and remove all values
                    var $labels = $phaseTemplate.find(".control-label");
                    var $inputs = $phaseTemplate.find(".phaseTrigger");

                    $labels.each(function () {
                        var oldFor = $(this).attr("for").split("-")[0];
                        var newFor = oldFor + "-" + newPhase;
                        console.log(newFor);
                        $(this).attr("for", newFor);
                        console.log($(this).attr("for"));
                    });

                    $inputs.each(function () {
                        var oldId = $(this).attr("id").split("-")[0];
                        var oldName = $(this).attr("name").split("-")[0];
                        var newId = oldId + "-" + newPhase;
                        var newName = oldName + "-" + newPhase;

                        $(this).attr("id", newId);
                        $(this).attr("name", newName);

                        $(this).val("");
                    });

                    $phaseTemplate.hide();
                    $("#phases").append($phaseTemplate);
                    $phaseTemplate.slideDown();

                    console.log($phaseTemplate);
                }
                console.log(triggerPhaseNumber);
            }
            else
            {
                // Check if all inputs are empty
            }
        });

        // When submitting the create project form, get the offset of the picture and put it in the input field
        $("form[name=create]").submit(function (e) {
            // Search the image and get the left offset
            var leftOffset = $(this).find("#imagePlaceholder img").css("left");
            if (leftOffset == 'auto') {
                leftOffset = "0px";
            }
            // Put the offset in the input field
            $(this).find("#photoOffset").val(leftOffset);
        });
    });
})(jQuery);