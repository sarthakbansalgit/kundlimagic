 
        <!-- Contact Hero -->
        <section class="as_breadcrum_wrapper" style="margin-top: 20px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h1 class="as_heading as_heading_center">Get in Touch</h1>
                        <p class="as_font14 as_margin0">We’d love to hear from you. Reach out for support, feedback, or partnerships.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="as_contact_section as_padderTop50 as_padderBottom50">
            <div class="container">
                <div class="row">
                    <!-- Info Column -->
                    <div class="col-lg-5 col-md-12">
                        <div class="as_journal_box_wrapper" style="padding: 25px; border-radius: 12px;">
                            <h2 class="as_heading" style="margin-bottom: 10px;">Contact Information</h2>
                           
                            <div class="row as_padderTop20">
                                <div class="col-sm-6 col-12">
                                    <div class="as_info_box" style="display:flex; align-items:center; gap:12px;">
                                        <span class="as_icon" style="min-width:48px; height:48px; display:flex; align-items:center; justify-content:center; background: rgba(255,112,16,0.15); border-radius:50%;">
                                            <img src="<?php echo base_url();?>assets/images/svg/call1.svg" alt="Call icon">
                                        </span>
                                        <div class="as_info">
                                            <h5 style="margin:0 0 4px;">Call Us</h5>
                                            <p class="as_margin0 as_font14">+ (91) 9509217591</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 as_padderTop10 as_padderBottom10">
                                    <div class="as_info_box" style="display:flex; align-items:center; gap:12px;">
                                        <span class="as_icon" style="min-width:48px; height:48px; display:flex; align-items:center; justify-content:center; background: rgba(255,112,16,0.15); border-radius:50%;">
                                            <img src="<?php echo base_url();?>assets/images/svg/map.svg" alt="Map icon">
                                        </span>
                                        <div class="as_info">
                                            <h5 style="margin:0 0 4px;">Visit Us</h5>
                                            <p class="as_margin0 as_font14">F-60, Apna Bazar, Lata Circle, Jhotwara, Jaipur, Rajasthan (302012)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Column -->
                    <div class="col-lg-7 col-md-12 as_padderTop20">
                        <div class="as_journal_box_wrapper" style="padding: 25px; border-radius: 12px;">
                            <h4 class="as_subheading" style="margin-bottom: 10px;">Have A Question?</h4>
                            <div id="contactResponse" style="margin-bottom:12px;"></div>
                            <form id="contactForm" method="post" novalidate>
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group"style="margin-left: 20px;">
                                            <label for="contact_name">Full Name</label>
                                            <input id="contact_name" type="text" name="name" class="form-control" placeholder="Your full name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="contact_email">Email Address</label>
                                            <input id="contact_email" type="email" name="email" class="form-control" placeholder="you@example.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="contact_number">Contact Number</label>
                                            <input id="contact_number" type="tel" name="number" class="form-control" placeholder="10-digit mobile" pattern="[0-9]{10}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="contact_subject">Subject</label>
                                            <input id="contact_subject" type="text" name="subject" class="form-control" placeholder="How can we help?" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="contact_message">Message</label>
                                            <textarea id="contact_message" name="msg" class="form-control" rows="4" placeholder="Write your message" required></textarea>
                                        </div>
                                    </div>

                                    <!-- Honeypot field -->
                                    <div style="position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden;">
                                        <input type="text" name="hp_field" tabindex="-1" autocomplete="off">
                                    </div>

                                    <div class="col-12 text-right">
                                        <button type="submit" class="as_btn" id="contactSubmitBtn">
                                            <span class="btn-text">Submit</span>
                                            <span class="btn-spinner" style="display:none;">Processing...</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Map Section -->
        <section class="as_map_section as_padderBottom80">
            <iframe title="KundliMagic office location map" src="https://maps.google.com/maps?q=F-60,%20Apna%20Bazar,%20Lata%20Circle,%20Jhotwara,%20Jaipur,%20Rajasthan%20302012&t=&z=14&ie=UTF8&iwloc=&output=embed" width="100%" height="400" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>

        <script>
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $('#contactSubmitBtn');
        const $btnText = $btn.find('.btn-text');
        const $btnSpinner = $btn.find('.btn-spinner');

        if (!$form[0].checkValidity()) {
            $('#contactResponse').html('<div class="alert alert-danger" role="alert">Please fill all required fields correctly.</div>');
            return;
        }

        // Disable button and show spinner
        $btn.prop('disabled', true);
        $btnText.hide();
        $btnSpinner.show();

        $.ajax({
            url: '<?= base_url("frontend/contact/insert_data") ?>',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                let res;
                try { res = JSON.parse(response); } catch(e) { res = { status: 'Error', message: 'Unexpected server response.' }; }
                const ok = res.status && res.status.toLowerCase() === 'success';
                const alertClass = ok ? 'alert-success' : 'alert-danger';
                $('#contactResponse').html('<div class="alert ' + alertClass + '" role="alert">' + (res.message || '') + '</div>');
                if (ok) { $form[0].reset(); }
            },
            error: function(xhr, status, error) {
                $('#contactResponse').html('<div class="alert alert-danger" role="alert">' + (error || 'Request failed') + '</div>');
            },
            complete: function() {
                // Re-enable button and hide spinner
                $btn.prop('disabled', false);
                $btnSpinner.hide();
                $btnText.show();
                setTimeout(function() {
                    $('#contactResponse').fadeOut('slow', function() { $(this).html('').show(); });
                }, 3000);
            }
        });
    });
</script>