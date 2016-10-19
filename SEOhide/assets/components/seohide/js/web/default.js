if (window.jQuery) {
    $(document).ready(function() {
        $('[hashString]').each(function () {
            var key = $(this).attr('hashString');
            if ($(this).attr('hashType') == 'href' && seoHrefs.hasOwnProperty(key)) {
                $(this).attr('href', Base64.decode(seoHrefs[key]));
            }
        });
    });
} else {
    console.log("Подключите JQUERY");
}

