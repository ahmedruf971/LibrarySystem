<footer style="
    background-color: var(--header-bg);
    background-image: url('images/scribble_footer.png');
    background-repeat: repeat;
    background-position: center bottom;
    background-size: 400px auto;
    color: white; /* FORCE white text */
    padding: 30px 20px;
    text-align: center;
    font-size: 14px;
    border-top: 1px solid rgba(255,255,255,0.1);
    margin-top: auto;
    position: relative;
  ">
  <div style="
      max-width: 700px;
      margin: 0 auto;
      position: relative;
      z-index: 1;
    ">
    <div style="display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap; margin-bottom: 15px;">
      <img src="images/logo.png" alt="Library Logo" style="width: 80px; height: auto;">
      <h2 style="margin: 0; font-size: 20px; color: white;">Codex Library Management System</h2>
    </div>
    <p style="color: white;">Email: support@codexsystems.com | Phone: +44 7777 979797</p>
    <p style="color: white;">&copy; <?= date('Y') ?> Codex Systems. All rights reserved.</p>
  </div>

  <!-- Overlay to improve readability over image -->
  <div id="footerOverlay" style="
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.4); /* dark overlay by default */
      z-index: 0;
    "></div>
</footer>
</div> <!-- end of .page-wrapper -->
</body>
</html>

<script>
  // Adjust overlay brightness for light mode
  const isLight = localStorage.getItem('theme') === 'light';
  if (isLight) {
    document.body.classList.add('light');
    const overlay = document.getElementById('footerOverlay');
    if (overlay) {
      overlay.style.background = 'rgba(0, 0, 0, 0.5)'; // Darker overlay in light mode
    }
  }
</script>
