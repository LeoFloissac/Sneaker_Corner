<?php 
    $extraCSS = ["/sneaker_corner/css/contact.css"];
    include 'partials/header.php'; 
?>

<main class="page-main contact-page">
    <!-- Hero Section -->
    <section class="page-hero">
        <h1>Contact Us</h1>
        <p>Have a question or need help? We're here for you. Reach out and we'll get back to you as soon as possible.</p>
    </section>

    <div class="contact-container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2>Send Us a Message</h2>
                <p>Fill out the form below and our team will respond within 24 hours.</p>
                
                <form class="contact-form" action="#" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name *</label>
                            <input type="text" id="firstName" name="firstName" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name *</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Doe" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="john.doe@example.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="+84 123 456 789">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="" disabled selected>Select a subject</option>
                            <option value="product">Product Inquiry</option>
                            <option value="stock">Stock Availability</option>
                            <option value="store">Store Information</option>
                            <option value="feedback">Feedback</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" placeholder="How can we help you?" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info-section">
                <h2>Get in Touch</h2>
                
                <div class="contact-info-list">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="contact-info-content">
                            <h3>Email</h3>
                            <p><a href="mailto:contact@sneakercorner.vn">contact@sneakercorner.vn</a></p>
                            <p><a href="mailto:support@sneakercorner.vn">support@sneakercorner.vn</a></p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="contact-info-content">
                            <h3>Phone</h3>
                            <p><a href="tel:+842438251234">+84 24 3825 1234</a> (Hanoi)</p>
                            <p><a href="tel:+842838215678">+84 28 3821 5678</a> (Ho Chi Minh)</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="contact-info-content">
                            <h3>Business Hours</h3>
                            <p>Monday - Saturday: 9:00 AM - 9:00 PM</p>
                            <p>Sunday: 10:00 AM - 8:00 PM</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="contact-info-content">
                            <h3>Locations</h3>
                            <p>Hanoi • Ho Chi Minh City • Da Nang</p>
                            <p><a href="/sneaker_corner/app/views/stores.php">View all stores →</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="contact-faq">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>Can I buy sneakers online?</h3>
                <p>Currently, we only sell in-store. You can browse our collection online to check availability, then visit one of our stores to make your purchase.</p>
            </div>
            <div class="faq-item">
                <h3>How can I check if a size is available?</h3>
                <p>Visit our product pages to see available sizes, or contact us directly by phone or email for the most up-to-date stock information.</p>
            </div>
            <div class="faq-item">
                <h3>Do you offer reservations?</h3>
                <p>Yes! Contact your nearest store to reserve a pair for up to 48 hours. This ensures your size is available when you visit.</p>
            </div>
            <div class="faq-item">
                <h3>Are all products authentic?</h3>
                <p>Absolutely. We only sell 100% authentic sneakers sourced directly from official brand distributors. Every pair comes with a warranty.</p>
            </div>
        </div>
    </section>
</main>

<?php include 'partials/footer.php'; ?>
