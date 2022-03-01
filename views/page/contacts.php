<div class="container">
    <div class="row content content-formal">
        <div class="col-xl-6 col-lg-8 offset-xl-2">
            <section id="contacts">
                <h3 class="section-heading mt-0">Contact Us</h3>
                <?php if ($this->reply_email) {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <p class="alert-heading subheading mb-1">Message sent!</p>
                        <p class="par-1 mb-0">
                            Thank you for your feedback, our manager will answer you soon <br>
                            on <a target="_blank" href="https://mail.google.com"><?= $this->reply_email ?></a>.
                            <br><br> With love and care, <?= ucfirst($GLOBALS['website']['name']) ?></p>
                    </div>
                    <?php
                } ?>
                <p class="par-1">
                    If you have any questions for us about Letshandmake, you can email <a
                            href="mailto:<?= $this->email ?>"><?= $this->email ?></a> or use contact form.</p>
                <p class="par-1">Contact via form or email does not affect response time. <br>
                    You can go ahead with both options.</p>

                <form action="" method="post">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Full name:</label>
                                <input name="name" class="form-control" placeholder="" type="text" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Email address:</label>
                                <input name="email" class="form-control" placeholder="" type="text" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Message:</label>
                                <textarea name="message" class="form-control"
                                          placeholder="Describe your problem or anwer a question..." rows="4"
                                          type="text" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-md btn-secondary">Send</button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>