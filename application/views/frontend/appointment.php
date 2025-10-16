<script src="https://mercury.phonepe.com/web/bundle/checkout.js"></script>

<section class="as_appointment_wrapper as_padderTop80 as_padderBottom80">
  <div class="container-fluid" style="max-width: 1200px;">
    <div class="row">
      <div class="col-12">
        <div class="kundli-form-container">
          <div class="kundli-header">
            <i class="fas fa-star-of-david" aria-hidden="true"></i>
            <h2 class="as_heading as_heading_center">Generate Your Personalized Kundli</h2>
            <!-- Removed unwanted PDF/dashboard text -->
          </div>

           

  

        
  <div class="as_journal_box_wrapper kundli-form-body">
          <form id="genrateKundli" method="post">
           
            <!-- Response messages container -->
              <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert" style="margin-bottom: 15px;">
                  <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
              <?php endif; ?>
              <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert" style="margin-bottom: 15px;">
                  <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php endif; ?>
            <div id="response" style="margin-bottom: 20px;"></div>

            <!-- Test button for filling form -->
            <div style="margin-bottom: 20px; text-align: center;">
              <button type="button" id="fillFormBtn" class="as_btn" style="background: #ff9800; margin-right: 10px;">Fill Form with Test Data</button>
              <button type="button" id="clearFormBtn" class="as_btn" style="background: #f44336;">Clear Form</button>
            </div>
           
            <!-- Moved kundli type selection below language dropdown -->

            <div class="form-section"><h5>Birth Details</h5></div>
            <div class="row form-grid">
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="name">NAME</label>
                <div class="form-group input-icon">
                  <i class="fas fa-user"></i>
                  <input class="form-control custom-input" id="name" type="text" placeholder="Full Name" name="name" required/>
                </div>
              </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="phone">Phone No.</label>
              <div class="form-group input-icon">
                <i class="fas fa-phone"></i>
                <input class="form-control" id="phone" type="tel" placeholder="Phone No." name="phone" pattern="[0-9]{10}" maxlength="10" required />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label for="email">Email (Optional)</label>
              <div class="form-group input-icon">
                <i class="fas fa-envelope"></i>
                <input class="form-control" id="email" type="email" placeholder="Email Address" name="email"/>
              </div>
            </div>

             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="gender">GENDER</label>
                <div class="form-group as_select_box">
                  <select class="form-control" id="gender" data-placeholder="Gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="dob">DATE OF BIRTH</label>

                <div class="form-group input-icon">
                <i class="fas fa-calendar"></i>
               <input
  class="form-control"
  id="dob"
  type="text"
  placeholder="DD-MM-YYYY"
  name="dob"
  onfocus="this.type='date'; this.click();"
  onblur="if(!this.value) this.type='text';"
/>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="tob">TIME OF BIRTH</label>
                <div class="form-group input-icon">
<i class="fas fa-clock"></i>
<input
  class="form-control"
  id="tob"
  type="text"
  placeholder="HH:MM"
  name="tob"
  onfocus="this.type='time'; this.click();"
  onblur="if(!this.value) this.type='text';"
/>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="placeOfBirth">BIRTH TOWN / CITY</label>
                <p>(Start Typing,then choose nearest place from list)</p>
                <div class="form-group input-icon">
                  <i class="fas fa-map-marker-alt"></i>
                  <input
                    class="form-control"
                    type="text"
                    id="placeOfBirth"
                    placeholder="Start Typing..."
                    autocomplete="off"
                    name="pob"
                    required
                  />
                  <div id="placeSuggestions" class="place-suggestions dropdown-menu"></div>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="language">KUNDLI LANGUAGE</label>
                <div class="form-group as_select_box input-icon">
                  <i class="fas fa-language"></i>
                  <select class="form-control" id="language" data-placeholder="Language" name="language">
                    <option value="hi">Hindi</option>
                    <option value="en">English</option>
                  </select>
            <!-- Kundli Type Selection - Beautiful Full Width -->
            <div class="kundli-type-section" style="margin:32px 0 24px 0; width:100%;">
              <h5 style="font-weight:600; color:#ffa500; margin-bottom:18px; text-align:left; letter-spacing:1px;">Select Kundli Type</h5>
              <div style="display:flex; flex-direction:row; gap:32px; justify-content:space-between; align-items:stretch; width:100%;">
                <div style="flex:1 1 0; min-width:0;">
                  <input type="radio" id="plan_basic" name="kundli_type" value="basic" checked hidden>
                  <label class="plan-card beautified" for="plan_basic" style="display:flex; flex-direction:column; align-items:center; background:linear-gradient(135deg,#fffbe6 0%,#ffe0b2 100%); border:2px solid #ffa500; border-radius:18px; padding:32px 24px; box-shadow:0 2px 12px rgba(255,112,16,0.08); cursor:pointer; transition:all .2s; width:100%; min-height:160px;">
                    <div class="plan-img-wrap" style="margin-bottom:18px;">
                      <i class="fas fa-scroll" style="font-size:44px; color:#ffa500;"></i>
                    </div>
                    <div class="plan-meta" style="text-align:center;">
                      <h5 style="font-size:22px; font-weight:700; color:#ff9800; margin-bottom:8px;">Basic Kundli</h5>
                      <p class="price" style="font-size:18px; color:#333; margin-bottom:0;">₹51</p>
                    </div>
                    <span class="plan-badge" style="background:#ff9800; color:#fff; font-size:13px; border-radius:8px; padding:4px 12px; margin-top:14px; display:inline-block;">Popular</span>
                  </label>
                </div>
                <div style="flex:1 1 0; min-width:0;">
                  <input type="radio" id="plan_detailed" name="kundli_type" value="detailed" hidden>
                  <label class="plan-card beautified" for="plan_detailed" style="display:flex; flex-direction:column; align-items:center; background:linear-gradient(135deg,#e3f2fd 0%,#bbdefb 100%); border:2px solid #2196f3; border-radius:18px; padding:32px 24px; box-shadow:0 2px 12px rgba(33,150,243,0.08); cursor:pointer; transition:all .2s; width:100%; min-height:160px;">
                    <div class="plan-img-wrap" style="margin-bottom:18px;">
                      <i class="fas fa-moon" style="font-size:44px; color:#2196f3;"></i>
                    </div>
                    <div class="plan-meta" style="text-align:center;">
                      <h5 style="font-size:22px; font-weight:700; color:#2196f3; margin-bottom:8px;">Detailed Kundli</h5>
                      <p class="price" style="font-size:18px; color:#333; margin-bottom:0;">₹101</p>
                    </div>
                    <span class="plan-badge alt" style="background:#2196f3; color:#fff; font-size:13px; border-radius:8px; padding:4px 12px; margin-top:14px; display:inline-block;">Best Value</span>
                  </label>
                </div>
              </div>
            </div>
                </div>
              </div>
              <div class="form-group">
                  <input
                    class="form-control"
                    type="hidden"
                    name="lat"
                    placeholder="Mobile Number"
                  />
              </div>
              <div class="form-group">
                  <input
                    class="form-control"
                    type="hidden"
                    name="long"
                    placeholder="Mobile Number"
                  />
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center as_padderTop20">
                <button class="as_btn" id="genrateKundliBtn" style="width:100%;">Generate Kundli</button>
              </div>
            </div>
          </form>
        </div>
          <div class="kundli-help">
            <p style="margin:0 0 6px 0;">
              If you have completed the payment but haven’t received your Kundli, share a screenshot of the successful transaction with us at
              <a href="mailto:help@kundlimagic.com">help@kundlimagic.com</a>.
            </p>
            <p style="margin:0;">We’ll verify and deliver your PDF as soon as possible. Thank you for your patience.</p>
          </div>
        </div> <!-- Close kundli-form-container -->
      </div>
    </div>
  </div>
</section>





<script>
  document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('placeOfBirth');  // original input id
    const resultsDiv = document.getElementById('placeSuggestions');  // original suggestions container
    resultsDiv.style.display = 'none';
    

    let debounceTimer;

    input.addEventListener('input', function () {
      const query = input.value.trim();
      clearTimeout(debounceTimer);

      if (query.length < 2) {
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';
        input.style.borderColor = '';
        return;
      }

      // Show loading state
      input.style.borderColor = '#ffa500';
      resultsDiv.innerHTML = '<div class="dropdown-item disabled text-white-50">🔍 Searching locations...</div>';
      resultsDiv.style.display = 'block';

      debounceTimer = setTimeout(() => searchPlaces(query), 300);
    });

    async function searchPlaces(query) {
      // Using OpenStreetMap Nominatim API (free, no API key required)
      const endpoint = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=in&limit=10&addressdetails=1`;

      try {
        const response = await fetch(endpoint, {
          method: 'GET',
          headers: {
            'User-Agent': 'KundaliMagic/1.0'
          }
        });

        if (!response.ok) throw new Error('API error');

        const data = await response.json();
        const places = data.filter(place => isValidPlace(place));
        displayResults(places);
      } catch (error) {
        console.error('Place search failed:', error);
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';
      }
    }

    function isValidPlace(place) {
      // Filter for cities, towns, villages, and other relevant place types
      const validTypes = ['city', 'town', 'village', 'administrative', 'municipality'];
      const placeType = place.type || '';
      const placeClass = place.class || '';
      
      return (
        validTypes.some(type => placeType.includes(type) || placeClass.includes(type)) ||
        place.addresstype === 'city' ||
        place.addresstype === 'town' ||
        place.addresstype === 'village' ||
        (place.address && (place.address.city || place.address.town || place.address.village))
      );
    }

    function displayResults(places) {
      resultsDiv.innerHTML = '';
      if (places.length === 0) {
        resultsDiv.innerHTML = '<div class="dropdown-item disabled text-white-50">No matching places found.</div>';
        resultsDiv.style.display = 'block';
        return;
      }

      places.forEach(place => {
        const div = document.createElement('div');
        div.className = 'dropdown-item text-white';
        div.style.cssText = 'background: rgba(255, 140, 0, 0.1); border-bottom: 1px solid rgba(255, 140, 0, 0.3); cursor: pointer;';
        
        const lat = parseFloat(place.lat).toFixed(6);
        const lng = parseFloat(place.lon).toFixed(6);
        
        // Create display text from OpenStreetMap data
        let displayText = place.display_name;
        if (place.address) {
          const city = place.address.city || place.address.town || place.address.village || '';
          const state = place.address.state || '';
          const country = place.address.country || '';
          if (city && state) {
            displayText = `${city}, ${state}, ${country}`;
          }
        }
        
        div.textContent = displayText;
        div.dataset.lat = lat;
        div.dataset.lng = lng;

        // Hover effects
        div.addEventListener('mouseenter', () => {
          div.style.background = 'rgba(255, 140, 0, 0.3)';
        });
        
        div.addEventListener('mouseleave', () => {
          div.style.background = 'rgba(255, 140, 0, 0.1)';
        });

        // Use mousedown to handle selection before blur event
        div.addEventListener('mousedown', (e) => {
          e.preventDefault(); // prevent input blur before click

          input.value = displayText;
          const latInput = document.querySelector('input[name="lat"]');
          const lngInput = document.querySelector('input[name="long"]');
          if (latInput) latInput.value = lat;
          if (lngInput) lngInput.value = lng;

          resultsDiv.style.display = 'none';
          
          // Visual feedback
          input.style.borderColor = '#ff8c00';
          setTimeout(() => {
            input.style.borderColor = '';
          }, 1000);
        });

        resultsDiv.appendChild(div);
      });
      resultsDiv.style.display = 'block';
    }

    // Hide dropdown on input blur with a slight delay for click to register
    input.addEventListener('blur', () => {
      setTimeout(() => {
        resultsDiv.style.display = 'none';
      }, 100);
    });
  });
</script>






<script>
    $('#genrateKundli').on('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = $('#genrateKundliBtn');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        $('#response').html('<div class="alert alert-info" role="alert"><i class="fas fa-spinner fa-spin"></i> Processing your request...</div>');

            $.ajax({
            url: '<?= base_url("payment/generateKundli") ?>',
            type: 'POST',
            data: $(this).serialize(),
      success: function(response) {
        console.log('Response:', response); // Debug log
        try {
          const res = JSON.parse(response);

          // Check for successful PhonePe response with redirect URL
          if (res.code === 'SUCCESS' && res.data && res.data.instrumentResponse && res.data.instrumentResponse.redirectInfo && res.data.instrumentResponse.redirectInfo.url) {
            // Show loading message
            $('#response').html('<div class="alert alert-info" role="alert"><i class="fas fa-spinner fa-spin"></i> Redirecting to PhonePe payment gateway...</div>');
            
            // Redirect to PhonePe checkout after a short delay
            setTimeout(function() {
              window.location.href = res.data.instrumentResponse.redirectInfo.url;
            }, 1000);
            return;
          }

          // Handle different response formats
          if (res.status === 'success' && res.redirectUrl) {
            $('#response').html('<div class="alert alert-info" role="alert"><i class="fas fa-spinner fa-spin"></i> Redirecting to payment gateway...</div>');
            setTimeout(function() {
              window.location.href = res.redirectUrl;
            }, 1000);
            return;
          }

          // If server returned an error or unexpected payload
          let message = res.message || res.error || 'Unexpected response from server. Please try again.';
          $('#response').html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> ' + message + '</div>');
          setTimeout(function() {
            $('#response').fadeOut('slow', function() { $(this).html('').show(); });
          }, 5000);
        } catch(e) {
          console.error('JSON Parse Error:', e);
          console.log('Raw response:', response);
          $('#response').html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Error processing response. Please try again.</div>');
          setTimeout(function() {
            $('#response').fadeOut('slow', function() { $(this).html('').show(); });
          }, 5000);
        }
        
        // Reset button state
        submitBtn.prop('disabled', false).html(originalText);
      },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText); // Debug log
                console.error('Status:', status);
                console.error('Error:', error);
                
                let errorMessage = 'Request failed. Please check your internet connection and try again.';
                if (xhr.responseText) {
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMessage = errorResponse.message || errorMessage;
                    } catch(e) {
                        // Use default message if response is not JSON
                    }
                }
                
                $('#response').html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> ' + errorMessage + '</div>');

                // Reset button state
                submitBtn.prop('disabled', false).html(originalText);

                // Hide after 5 seconds
                setTimeout(function() {
                    $('#response').fadeOut('slow', function() {
                        $(this).html('').show();
                    });
                }, 5000);
            }
        });
    });

function genratePdf() {
  $.ajax({
    url: '<?= base_url("payment/generate_pdf") ?>',
    type: 'GET',
  
    success: function(response) {
        const res = JSON.parse(response);
        
        if(res.status === 200){
          $('#genrateKundli')[0].reset();
          // $('#genrateKundliBtn')
          //     .removeClass('btn-disabled')
          //     .prop('disabled', false)
          //     .text('Genrate kundli');
          // $('#downloadKundliBtn')
          //   .attr('href', res.response)
          window.location.href = '/downloadpdf';
        

        }else{
          let alertClass = 'alert-danger';
          $('#response').html('<div class="alert ' + alertClass + '" role="alert">Payment Unsuccessfull please try again later or contact us!</div>');

          setTimeout(function() {
              $('#response').fadeOut('slow', function() {
                  $(this).html('').show();
              });
          }, 3000);
  }
    },
    error: function(xhr, status, error) {
        $('#response').html('<div class="alert alert-danger" role="alert">' + error + '</div>');

        // Hide after 3 seconds
        setTimeout(function() {
            $('#response').fadeOut('slow', function() {
                $(this).html('').show();
            });
        }, 3000);
    }
});
}

// Function to generate random test data
function fillFormWithTestData() {
    // Random names
    const names = ['Rahul Sharma', 'Priya Patel', 'Amit Kumar', 'Sneha Singh', 'Vikram Joshi', 'Anjali Gupta', 'Rajesh Verma', 'Kavita Agarwal', 'Sandeep Yadav', 'Meera Choudhary'];
    const cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Lucknow', 'Kanpur'];
    
    // Fill name
    $('#name').val(names[Math.floor(Math.random() * names.length)]);
    
    // Fill phone (10 digits)
    const phone = '9' + Math.floor(Math.random() * 900000000 + 100000000);
    $('#phone').val(phone);
    
    // Fill email (optional)
    const email = $('#name').val().toLowerCase().replace(' ', '.') + '@test.com';
    $('#email').val(email);
    
    // Fill gender
    const genders = ['male', 'female'];
    $('#gender').val(genders[Math.floor(Math.random() * genders.length)]);
    
    // Fill date of birth (between 1980-2000)
    const start = new Date(1980, 0, 1);
    const end = new Date(2000, 11, 31);
    const randomDate = new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
    const dob = randomDate.toISOString().split('T')[0];
    $('#dob').val(dob);
    
    // Fill time of birth
    const hours = Math.floor(Math.random() * 24).toString().padStart(2, '0');
    const minutes = Math.floor(Math.random() * 60).toString().padStart(2, '0');
    const tob = hours + ':' + minutes;
    $('#tob').val(tob);
    
    // Fill place of birth
    $('#placeOfBirth').val(cities[Math.floor(Math.random() * cities.length)] + ', India');
    
    // Set coordinates (dummy values for Indian cities)
    $('input[name="lat"]').val((8 + Math.random() * 10).toFixed(6)); // Lat between 8-18
    $('input[name="long"]').val((68 + Math.random() * 15).toFixed(6)); // Long between 68-83
    
    // Fill language
    const languages = ['hi', 'en'];
    $('#language').val(languages[Math.floor(Math.random() * languages.length)]);
    
    // Fill kundli type (random between basic and detailed)
    const kundliTypes = ['basic', 'detailed'];
    const selectedType = kundliTypes[Math.floor(Math.random() * kundliTypes.length)];
    $('input[name="kundli_type"][value="' + selectedType + '"]').prop('checked', true);
    
    console.log('Form filled with test data!');
}

// Function to clear form
function clearForm() {
    $('#genrateKundli')[0].reset();
    $('input[name="lat"]').val('');
    $('input[name="long"]').val('');
    console.log('Form cleared!');
}

// Event listeners
$(document).ready(function() {
    $('#fillFormBtn').on('click', fillFormWithTestData);
    $('#clearFormBtn').on('click', clearForm);
});
</script>
<script>
$(document).ready(function() {
  // Highlight selected kundli type visually
  function updateKundliTypeHighlight() {
    $("input[name='kundli_type']").each(function() {
      var label = $("label[for='" + $(this).attr('id') + "']");
      if ($(this).is(':checked')) {
        label.addClass('active-kundli-type');
      } else {
        label.removeClass('active-kundli-type');
      }
    });
  }
  $("input[name='kundli_type']").on('change', updateKundliTypeHighlight);
  updateKundliTypeHighlight(); // Initial highlight
});
</script>
<style>
.active-kundli-type {
  box-shadow: 0 0 0 4px #ffa50055;
  border-color: #ff9800 !important;
  background: linear-gradient(135deg, #fffbe6 0%, #ffe0b2 100%) !important;
  transition: box-shadow 0.2s, border-color 0.2s;
}
.active-kundli-type.alt {
  box-shadow: 0 0 0 4px #2196f355;
  border-color: #2196f3 !important;
  background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
}
</style>