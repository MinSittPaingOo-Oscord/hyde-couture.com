<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Footer - Hyde Couture</title>
    <style>
      :root {
        --rolex-green: #228b22;
        --dark-bg: #1a1a1a;
        --light-bg: #ffffff;
      }

      body {
        font-family: "Vollkorn", serif;
        background-color: var(--light-bg);
        color: #333;
        margin: 0;
        padding: 0;
      }

      /* Footer Styles */
      .footer {
        background-color: #111;
        color: #aaa;
        padding: 4rem 0 2rem;
      }

      .footer h5 {
        color: white;
        margin-bottom: 1.5rem;
        font-family: "Cinzel", serif;
      }

      .footer-links {
        list-style: none;
        padding: 0;
      }

      .footer-links li {
        margin-bottom: 10px;
      }

      .footer-links a {
        color: #aaa;
        text-decoration: none;
        transition: color 0.3s;
      }

      .footer-links a:hover {
        color: white;
      }

      .social-icons a {
        display: inline-block;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        margin-right: 10px;
        transition: all 0.3s;
      }

      .social-icons a:hover {
        background-color: var(--rolex-green);
        transform: translateY(-3px);
      }

      .copyright {
        border-top: 1px solid #333;
        padding-top: 2rem;
        margin-top: 3rem;
        text-align: center;
        color: #777;
      }

      
      /* Newsletter Section */
      .newsletter {
        background-color: var(--dark-bg);
        color: white;
        padding: 4rem 0;
      }

      .newsletter h3 {
        margin-bottom: 1.5rem;
      }

      .newsletter p {
        margin-bottom: 2rem;
        color: #ccc;
      }

      .newsletter-form .form-control {
        background-color: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        padding: 12px 15px;
      }

      .newsletter-form .form-control::placeholder {
        color: #aaa;
      }

      /* Mobile Responsive */
      @media (max-width: 768px) {
        .footer {
          padding: 3rem 0 1rem;
          text-align: center;
        }

        .footer .col-md-4,
        .footer .col-md-2 {
          margin-bottom: 2rem;
        }

        .social-icons {
          justify-content: center;
        }
        
         /* Newsletter */
         .newsletter {
          padding: 3rem 0;
          text-align: center;
        }

        .newsletter-form .input-group {
          flex-direction: column;
          gap: 10px;
        }

        .newsletter-form .form-control {
          margin-bottom: 10px;
        }


      }
    </style>
  </head>
  <body>


  <section class="newsletter">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h3>Stay Updated</h3>
            <p>
              Subscribe to our newsletter to receive exclusive offers, style
              tips, and early access to new collections.
            </p>
          </div>
          <div class="col-md-6">
            <form class="newsletter-form">
              <div class="input-group">
                <input
                  type="email"
                  class="form-control"
                  placeholder="Your email address"
                />
                <button class="btn btn-primary" type="submit">Subscribe</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4 mb-4">
            <h5>HYDE COUTURE</h5>
            <p>
              Premium clothing for the modern individual who values quality,
              comfort, and timeless elegance.
            </p>
            <div class="social-icons">
              <a href="#"><i class="fab fa-facebook-f"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-pinterest"></i></a>
            </div>
          </div>
          <div class="col-md-2 mb-4">
            <h5>Shop</h5>
            <ul class="footer-links">
              <li><a href="#">Men's Collection</a></li>
              <li><a href="#">Women's Collection</a></li>
              <li><a href="#">Accessories</a></li>
              <li><a href="#">New Arrivals</a></li>
              <li><a href="#">Sale</a></li>
            </ul>
          </div>
          <div class="col-md-2 mb-4">
            <h5>Company</h5>
            <ul class="footer-links">
              <li><a href="#">About Us</a></li>
              <li><a href="#">Careers</a></li>
              <li><a href="#">Sustainability</a></li>
              <li><a href="#">Press</a></li>
              <li><a href="#">Affiliates</a></li>
            </ul>
          </div>
          <div class="col-md-2 mb-4">
            <h5>Support</h5>
            <ul class="footer-links">
              <li><a href="#">Contact Us</a></li>
              <li><a href="#">Shipping Info</a></li>
              <li><a href="#">Returns</a></li>
              <li><a href="#">Size Guide</a></li>
              <li><a href="#">FAQ</a></li>
            </ul>
          </div>
          <div class="col-md-2 mb-4">
            <h5>Legal</h5>
            <ul class="footer-links">
              <li><a href="#">Terms of Service</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Cookie Policy</a></li>
              <li><a href="#">Accessibility</a></li>
            </ul>
          </div>
        </div>
        <div class="copyright">
          <p>&copy; 2025 Elegance Attire. All rights reserved.</p>
        </div>
      </div>
    </footer>

    <!-- Font Awesome -->
    <script
      src="https://kit.fontawesome.com/a076d05399.js"
      crossorigin="anonymous"
    ></script>
  </body>
</html>