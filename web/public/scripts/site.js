/**
 * Created by Siebe on 22/04/2016.
 */
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imagePlaceholder img').attr('src', e.target.result);
            // console.log("loaded");
            showImage(); //Loaded here because other the image isn't loaded when handling width
        };
        // console.log("added");
        reader.readAsDataURL(input.files[0]);
    }
}

function showImage() {
    var $image = $("form[name=create] .upload #imagePlaceholder img");
    $image.show();
    $("form[name=create] .upload #imagePlaceholder label").hide();
    var widthImage = $image[0].clientWidth;
    var widthPlace = document.getElementById("imagePlaceholder").clientWidth;
    var leftOfHolder = $("#imagePlaceholder").offset().left;
    console.log(leftOfHolder);
    var containWidth = widthImage - widthPlace;
    console.log(containWidth);
    $image.draggable({
        containment: [leftOfHolder - containWidth + 1, 0, leftOfHolder + 1, 0], //1 px border on each side
        axis: "x",
        scroll: false
    });
}

$("#image").change(function () {
    readURL(this);
});