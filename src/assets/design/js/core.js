$(function() {

    // keep showing error response on hover
	$('[data-element="error"]').hover(function() {

		clearTimeout(keepErrorModule);
	}, function () {

		hideErrorModule();
	});

    // send contact form
    $(document).on("submit", "form#contact-form", function() {

        let formData, url, method, $t, $captcha;

        $t = $(this),
        url = location.href + "assets/functions/sendContact.php",
        formData = new FormData(this),
        method = $t.attr("method"),
        $captcha = $t.find("[data-element='captcha']");

        // console log passed objects
        for (var [key, value] of formData.entries()) { 
            //console.log(key, value);
        }
        
        $.ajax({
            url: url,
            data: formData,
            method: method,
            contentType: false,
            processData: false,
            success: function(data) {

                console.log(data);

                if(data.status !== true) {
                    reloadContent("[data-element='captcha']");
                } else {
                    reloadContent("[data-action='reloadContactForm']");
                }

                showErrorModule(false, data.message); 
                
            },
            error: function(data) {
                console.log(data);
            }
        });

        return false;
    });

    // rotate array
    $(document).on("submit", "form#rotate-form", function() {

        let formData, url, method, $t;

        $t = $(this),
        url = "ajax/rotateLeft",
        formData = new FormData(this),
        method = $t.attr("method"),
        $react = $t.find("[data-react='rotate']");
        
        $.ajax({
            url: url,
            data: formData,
            method: method,
            contentType: false,
            processData: false,
            success: function(data) {

                $react.html(data.message);
                
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });

        return false;
    });

});

let reloadContent = function(container) {
    
    let $container = $(container);
    $container.load(location.href + " " + container + ">*", "");
}

// hide error response automatically
let keepErrorModule,
hideErrorModule = function() {

	clearTimeout(keepErrorModule);

	keepErrorModule = setTimeout(function(){ 

        $('[data-element="error"]').css({ 
            "bottom":"-120px" 
        }); 
    }, 5000);	
}

// call this function for showing error response and pass
// response text as param
let showErrorModule = function(std = false, error = null) {

    var $errorModule = $('body').find('[data-element="error"]');
    
    hideErrorModule();
    if(std == false && error !== null) {
        $errorModule.find('p').html(error);
    } else {
        $errorModule.find('p').html("Something went wrong");
    }
    $errorModule.css({ "bottom":"24px" });
}