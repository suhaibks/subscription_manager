<?php include 'header.php'; ?>

<style>
  .contact-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
    text-align: center;
  }

  .contact-container h2 {
    font-size: 28px;
    margin-bottom: 40px;
    color: #222;
  }

  .team-grid {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
  }

  .team-card {
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    width: 280px;
    box-shadow: 0 0 12px rgba(0, 0, 0, 0.06);
    transition: transform 0.2s ease;
  }

  .team-card:hover {
    transform: translateY(-5px);
  }

  .team-card img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    margin-bottom: 15px;
  }

  .team-card h3 {
    font-size: 18px;
    margin: 8px 0 5px;
  }

  .team-card p {
    margin: 0;
    font-size: 14px;
    color: #555;
  }

  .team-card a {
    color: #007bff;
    text-decoration: none;
    font-size: 14px;
  }

  .team-card a:hover {
    text-decoration: underline;
  }
</style>

<div class="contact-container">
  <h2>Contact Us</h2>
  <div class="team-grid">
    <div class="team-card">
      <img src="assets/images/suhaib.jpeg" alt="Suhaib K S">
      <h3>Suhaib K S</h3>
      <p>Full-Stack Developer</p>
      <p>Email: <a href="mailto:suhaibcoorg5@gmail.com">suhaibcoorg5@gmail.com</a></p>
    </div>
    <div class="team-card">
      <img src="assets/images/nishanth.jpeg" alt="Nishanth B N">
      <h3>Nishanth B N</h3>
      <p>Content Manager</p>
      <p>Email: <a href="mailto:nishunishanthy@gmail.com">nishunishanthy@gmail.com</a></p>
    </div>
    <div class="team-card">
      <img src="assets/images/akshatha.jpeg" alt="Akshatha M S">
      <h3>Akshatha M S</h3>
      <p>UI/UX Designer</p>
      <p>Email: <a href="mailto:akshatha086@gmail.com">akshatha086@gmail.com</a></p>
    </div>
  </div>
  </div> <!-- End of .team-grid -->

<div style="margin-top: 40px; height: 2px; background-color: #ddd; border-radius: 4px;"></div>

<div style="margin-top: 25px; font-size: 15px; color: #444; max-width: 800px; margin-left: auto; margin-right: auto; text-align: center;">
  <p>
    Our team is here to help you manage your subscriptions with ease. Whether you're an individual or a business, feel free to reach out with feedback, suggestions, or support queries. We aim to respond to all emails within 24 hours.
  </p>
</div>

</div>

<?php include 'footer.php'; ?>
