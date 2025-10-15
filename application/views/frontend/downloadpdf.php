

<?php
    $transactionId = $this->session->userdata('userId');
    $getpdfdata    = $this->db->where('merchantOrderId', $transactionId)
                             ->get('genrate_kundli')
                             ->row();
    $pdfurl        = $getpdfdata->pdfurl;
?>

<style>
  /* Responsive loader tweaks */
  .pdf-loader {
    text-align: center;
    position: relative;
    padding: 20px;
  }
  .step-icon {
    font-size: 6vw;              /* Scales with viewport width */
    max-font-size: 64px;         /* Caps at 64px */
    margin: 30px auto 50px;      /* Increased bottom margin */
    display: block;
    animation: pulse 2s infinite ease-in-out;
  }
  .step-text {
    font-size: 4vw;              /* Scales with viewport width */
    max-font-size: 18px;         /* Caps at 18px */
    margin: 0 auto 30px;         /* Increased bottom margin */
    min-height: 3em;
    color: #fff;                 /* White text */
    line-height: 1.2;
    padding: 0 10px;             /* Padding to avoid collision */
  }
  .progress-bar {
    width: 80%;
    max-width: 400px;
    height: 10px;
    background: rgba(255,255,255,0.2);
    border-radius: 5px;
    overflow: hidden;
    margin: 0 auto 20px;
  }
  .progress-bar-fill {
    height: 100%;
    background: #f9c74f;
    width: 0%;
    transition: width 1s ease;
  }
  @keyframes pulse {
    0%,100% { opacity: 1; }
    50%     { opacity: 0.6; }
  }

  /* Ensure download section is hidden initially */
  .download-pdf-container {
    display: none;
  }

  /* Extra responsive adjustment */
  @media (max-width: 480px) {
    .step-icon { font-size: 12vw; }
    .step-text { font-size: 5vw; }
    .progress-bar { width: 90%; }
  }
</style>

<section class="as_about_wrapper as_padderTop80 as_padderBottom80">
  <div class="container">
<h4 id="waiting-msg" style="color: #fff; text-align: center; margin: 20px auto;">
Please wait and do not go back; your Kundli is being prepared.
</h4> 

    <div class="pdf-loader" aria-live="polite" aria-atomic="true">
      <!-- existing Lottie -->
      <lottie-player
        src="<?php echo base_url();?>assets/loading.json"
        background="transparent"
        speed="1"
        style="width: 200px; height: 200px; margin: auto;"
        loop autoplay>
      </lottie-player>

      <!-- loader steps -->
      <div class="step-icon" id="step-icon">🪐</div>
      <div class="step-text" id="step-text">Calculating Planetary Positions...</div>

      <!-- progress bar -->
      <div class="progress-bar" aria-label="Loading progress">
        <div class="progress-bar-fill" id="progress-bar-fill"></div>
      </div>
    </div>

    <div class="download-pdf-container">
      <!-- existing “congratulations” Lottie -->
      <lottie-player
        src="<?php echo base_url();?>assets/cong.json"
        background="transparent"
        speed="1"
        style="width: 200px; height: 200px; margin: auto;"
        loop autoplay>
      </lottie-player>

      <h2 style="color: #fff; margin-top: 20px;">
Congratulations! Your PDF is ready. Please click the button below to download your Kundli.
      </h2>
      <a href="<?php echo $pdfurl; ?>" target="_blank" class="as_btn">Download Kundli</a>
    </div>

  </div>
</section>

<!-- New waiting message -->


<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const steps = [
  { icon: '🪐', text: 'Calculating Planetary Positions...' },
  { icon: '♈', text: 'Analyzing Zodiac Signs and Ascendant...' },
  { icon: '🌙', text: 'Matching Nakshatras and Houses...' },
  { icon: '📜', text: 'Studying Dashas and Yogas...' },
  { icon: '📊', text: 'Computing Divisional Charts...' },
  { icon: '⚖️', text: 'Assessing Planetary Strengths...' },
  { icon: '🔄', text: 'Performing Transit Analysis...' },
  { icon: '⚠️', text: 'Detecting Doshas and Malefics...' },
  { icon: '💡', text: 'Generating Remedial Suggestions...' },
  { icon: '✨', text: 'Finalizing Your Personalized Kundli...' }
    ];
    const totalSteps      = steps.length;
    const durationPerStep = 4000; // 9s × 5 = 45s
    let currentStep       = 0;

    const iconEl = document.getElementById('step-icon');
    const textEl = document.getElementById('step-text');
    const barEl  = document.getElementById('progress-bar-fill');

    function showStep(i) {
      if (i < totalSteps) {
        iconEl.textContent = steps[i].icon;
        textEl.textContent = steps[i].text;
        barEl.style.width   = ((i + 1) / totalSteps) * 100 + '%';
      }
    }

    function next() {
      showStep(currentStep);
      currentStep++;
      if (currentStep < totalSteps) {
        setTimeout(next, durationPerStep);
      } else {
        setTimeout(() => {
          document.querySelector('.pdf-loader').style.display             = 'none';
          document.getElementById('waiting-msg').style.display           = 'none';
          document.querySelector('.download-pdf-container').style.display = 'block';
        }, durationPerStep);
      }
    }
    next();
  });

  // Fallback countdown
  let timeLeft        = 45; // seconds
  const countdownEl   = document.getElementById("countdown");
  const countdownTimer = setInterval(() => {
    if (countdownEl) countdownEl.textContent = timeLeft;
    timeLeft--;
    if (timeLeft < 0) {
      clearInterval(countdownTimer);
      document.querySelector('.pdf-loader').style.display             = 'none';
      document.getElementById('waiting-msg').style.display           = 'none';
      document.querySelector('.download-pdf-container').style.display = 'block';
    }
  }, 1000);
</script>
