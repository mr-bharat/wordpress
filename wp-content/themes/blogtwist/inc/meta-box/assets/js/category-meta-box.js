jQuery(document).ready(function ($) {
    // Initialize variables
    var thumbnailId = $("#post_cat_thumbnail_id");
    var removeImageButton = $(".remove_image_button");
    var uploadImageButton = $(".upload_image_button");
    var postCatThumbnail = $("#post_cat_thumbnail");
    var fileFrame;

    // Hide "remove image" button if no image is set
    if (!thumbnailId.val() || "0" === thumbnailId.val()) {
        removeImageButton.hide();
    }

    // Function to handle media frame selection
    function handleMediaFrameSelect() {
        var attachment = fileFrame.state().get("selection").first().toJSON();
        var attachmentThumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

        thumbnailId.val(attachment.id);
        postCatThumbnail.find("img").attr("src", attachmentThumbnail.url);
        removeImageButton.show();
    }

    // Event handler for image upload button click
    uploadImageButton.on("click", function (event) {
        event.preventDefault();

        // Reopen media frame if it already exists
        if (fileFrame) {
            fileFrame.open();
            return;
        }

        // Create media frame
        fileFrame = wp.media({
            title: BlogTwistCatScript.title,
            button: {
                text: BlogTwistCatScript.btn_txt,
            },
            multiple: false,
        });

        // Bind select event
        fileFrame.on("select", handleMediaFrameSelect);

        // Open the modal
        fileFrame.open();
    });

    // Event handler for removing image
    removeImageButton.on("click", function () {
        postCatThumbnail.find("img").attr("src", BlogTwistCatScript.img);
        thumbnailId.val("");
        removeImageButton.hide();
        return false;
    });

    // Ajax complete event to clear thumbnail fields on form submit
    $(document).ajaxComplete(function (event, request, options) {
        if (request && request.readyState === 4 && request.status === 200 && options.data && options.data.includes("action=add-tag")) {
            var res = wpAjax.parseAjaxResponse(request.responseXML, "ajax-response");
            if (!res || res.errors) {
                return;
            }
            postCatThumbnail.find("img").attr("src", BlogTwistCatScript.img);
            thumbnailId.val("");
            removeImageButton.hide();
        }
    });

    // Initialize color picker
    $(".colorpicker").wpColorPicker();
});
