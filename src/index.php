<?php

require_once "mysql/functions.php";

$captcha = new Captcha($_SESSION);
$pageTitle = "Kontaktformular";

include_once "templates/head.php";

?>

<script>
    $(function() {

        let contact, contactHeight, windowHeight;

        contact = $(document).find("[data-element='contact']");
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

<section data-element="contact" data-action="reloadContactForm" class="shd-2">
    <form id="contact-form" name="contact-form" action="" method="POST" enctype="multipart/form-data">

        <div class="shd-1" style="z-index:1;position:relative;padding:36px 0;">

            <div class="padding mb24">
                <div class="col">
                    <div class="md-form mb-0">
                        <label for="email" class="mb8">Firstnamee</label>
                        <input type="text" id="email" name="firstname" class="form-control" value="fname" required>
                    </div>
                </div>
            </div>

            <div class="padding mb24">
                <div class="col">
                    <div class="md-form mb-0">
                        <label for="email" class="mb8">Lastname</label>
                        <input type="text" id="email" name="lastname" class="form-control" value="lname" required>
                    </div>
                </div>
            </div>

            <div class="padding mb24">
                <div class="col">
                    <div class="md-form mb-0">
                        <label for="email" class="mb8">Mail</label>
                        <input type="email" id="email" name="mail" class="form-control" value="test@mail.com" required>
                    </div>
                </div>
            </div>

            <div class="mb24">
                <div class="cl mb8 padding">
                    <label for="captcha" class="">Add attachement</label>
                </div>
                <div class="col">
                    <div class="md-form m-0">
                        <div id="add-file" class="chooser">
                            <p>
                                <input type="file" name="fileUpload" class="custom-file-input" id="chooseFile">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cl padding">
                <div class="cl mb-2">
                    <label for="captcha" class="">Are you robot?</label>
                </div>

                <div class="row" data-element="captcha" data-action="reloadCaptcha">
                    <div class="col">
                        <div class="md-form mb-0">
                            <div>
                                <div class="captcha">
                                    <?php foreach ($captcha->getCaptchaArray() as $zahl) { ?>
                                        <div class="captcha_zahl_<?= $zahl; ?> captcha_zahl">
                                            <div class="captcha_ecke"></div>
                                            <div class="captcha_w <?= $captcha->isActive($zahl, 'w1'); ?>" id="w1"></div>
                                            <div class="captcha_ecke"></div>
                                            <div style="clear:left;"></div>
                                            <div class="captcha_s <?= $captcha->isActive($zahl, 's1'); ?>" id="s1"></div>
                                            <div class="captcha_innen"></div>
                                            <div class="captcha_s <?= $captcha->isActive($zahl, 's2'); ?>" id="s2"></div>
                                            <div style="clear:left;"></div>
                                            <div class="captcha_ecke"></div>
                                            <div class="captcha_w <?= $captcha->isActive($zahl, 'w2'); ?>" id="w2"></div>
                                            <div class="captcha_ecke"></div>
                                            <div style="clear:left;"></div>
                                            <div class="captcha_s <?= $captcha->isActive($zahl, 's3'); ?>" id="s3"></div>
                                            <div class="captcha_innen"></div>
                                            <div class="captcha_s <?= $captcha->isActive($zahl, 's4'); ?>" id="s4"></div>
                                            <div style="clear:left;"></div>
                                            <div class="captcha_ecke"></div>
                                            <div class="captcha_w <?= $captcha->isActive($zahl, 'w3'); ?>" id="w3"></div>
                                            <div class="captcha_ecke"></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="captcha-input">
                        <input type="hidden" name="old_captcha" value="<?php echo $_SESSION["captcha_session"]; ?>" />
                        <input class="form-control p-2" type="text" name="captcha" size="6" maxlength="6" required />
                    </div>
                </div>
            </div>

        </div>

        <div>
            <button class="std-button" type="submit">Add contact</button>
        </div>

    </form>
</section>

<?php include_once "templates/footer.php"; ?>