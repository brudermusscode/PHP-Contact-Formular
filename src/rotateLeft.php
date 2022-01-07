<?php

require_once "mysql/functions.php";

$captcha = new Captcha($_SESSION);
$pageTitle = "Kontaktformular";

include_once "templates/head.php";

?>

<script>
    $(function() {

        let contact, contactHeight, windowHeight;

        contact = $(document).find("section");
        contactHeight = contact.outerHeight();
        windowHeight = $(window).height();

        if (contactHeight + 48 >= windowHeight) {
            contact.addClass("scrollable");
        }

        $(window).resize(function() {
            if (contactHeight + 48 >= $(this).height()) {
                contact.addClass("scrollable");
            } else {
                contact.removeClass("scrollable");
            }
        });

    });
</script>

<section data-element="rotate" class="shd-2">
    <form id="rotate-form" name="rotate-form" action="" method="POST" enctype="multipart/form-data">
        <div class="shd-1" style="z-index:1;position:relative;padding:36px 0;">

            <div class="padding mb24">
                <div class="col">
                    <div class="md-form mb-0">
                        <label for="email" class="mb8">Insert numbers (seperated by <span style="background:rgba(0,0,0,.12);padding:0 12px 4px;border-radius:4px;">space</span>)</label>
                        <input type="text" name="array" class="form-control" value="" placeholder="1 2 3 4 5" required>
                    </div>
                </div>
            </div>

            <div class="padding mb24">
                <div class="col">
                    <div class="md-form mb-0">
                        <label for="email" class="mb8">Rotate times</label>
                        <input type="text" name="d" class="form-control" value="" placeholder="4" required>
                    </div>
                </div>
            </div>

            <div class="padding">
                <div class="col">
                    <div class="md-form mb-0">
                        <p class="mb8">Output</p>
                    </div>
                </div>
            </div>

            <div>
                <p data-react="rotate" style="background:rgba(0,0,0,.04);text-align:center;padding:12px 0;color:rgba(0,0,0,.48);">Rotate something!</p>
            </div>

        </div>

        <div>
            <button class="std-button" type="submit">Rotate left!</button>
        </div>

    </form>
</section>