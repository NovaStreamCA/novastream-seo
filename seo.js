document.addEventListener("DOMContentLoaded", function () {
    // Select all meta tags
    var metaTags = document.querySelectorAll("meta[content='']");
    // Loop through and remove the empty tags
    metaTags.forEach(function (metaTag) {
        metaTag.parentNode.removeChild(metaTag);
    });
});
