var fullPhoneNumber;
var sendOtpButton = document.getElementById("send-otp-button");
var verifyOtpButton = document.getElementById("verify-otp-button");
var otpAttempts = 0;
var isRequestInProgress = false;
var sendOtpTimeout;
var verifyOtpTimeout;

sendOtpButton.addEventListener("click", () => {
  if (otpAttempts < 3) { // Check if the user has not exceeded the OTP attempts limit
    clearTimeout(sendOtpTimeout); // Clear any previous timeouts
    
    sendOtpButton.style.display = "none";
    var phoneNumberInput = document.getElementById("ff_19_num_cellulare");
    var selectedCountryListItem = document.querySelector(
      "li[data-country-code][aria-selected='true']"
    );

    if (selectedCountryListItem) {
      var dialCode = selectedCountryListItem.getAttribute("data-dial-code");
      var number = phoneNumberInput.value.replace(/[\s\-()+]/g, "");

      fullPhoneNumber = dialCode + number;

      // Make an AJAX request to send the fullPhoneNumber to the server
      jQuery.post(
        smsapi_params.ajax_url,
        {
          action: "send_sms",
          fullPhoneNumber: fullPhoneNumber,
          security: smsapi_params.nonce,
        },
        function (response) {
          // Handle the response from the server if needed
          console.log(response);
          otpAttempts++; // Increment the OTP attempts
          verifyOtpButton.style.display = "block";

          // Disable the send OTP button for one minute
          sendOtpTimeout = setTimeout(function () {
            sendOtpButton.style.display = "block";
          }, 60000); // 60,000 milliseconds = 1 minute
        }
      );
    }
  } else {
    alert("You have exceeded the OTP attempts limit. Please try again later.");
  }
});

verifyOtpButton.addEventListener("click", function () {
  if (otpAttempts < 3) { // Check if the user has not exceeded the OTP verification attempts limit
    var phoneNumberInput = document.getElementById("ff_19_num_cellulare");
    var selectedCountryListItem = document.querySelector(
      "li[data-country-code][aria-selected='true']"
    );

    if (!isRequestInProgress) {
      isRequestInProgress = true;
      if (selectedCountryListItem) {
        var userEnteredOTP = document.getElementById(
          "ff_19_codice_conferma"
        ).value;
        var dialCode = selectedCountryListItem.getAttribute("data-dial-code");
        var number = phoneNumberInput.value.replace(/[\s\-()+]/g, "");

        fullPhoneNumber = number;

        // Make an AJAX request to verify the OTP
        jQuery.post(
          smsapi_params.ajax_url,
          {
            action: "verify_otp",
            userEnteredOTP: userEnteredOTP,
            fullPhoneNumber: fullPhoneNumber,
            security: smsapi_params.nonce,
          },
          function (response) {
            // Handle the response from the server
            var httpCodeMatch = response.match(/HTTP\/\d+\s+(\d+)/);
            if (httpCodeMatch && httpCodeMatch.length > 1) {
              var httpCode = parseInt(httpCodeMatch[1]);

              if (httpCode === 204) {
                var responseContainer = document.getElementById(
                  "response-container"
                );
                responseContainer.innerHTML =
                  "Your OTP Is Verified Succesfully.";
                responseContainer.style.color = "Green";
                verifyOtpButton.style.display = "none";
              } else if (httpCode === 404) {
                var responseContainer = document.getElementById(
                  "response-container"
                );
                responseContainer.innerHTML =
                  "Your OTP Is Invalid. Please Enter the correct OTP.";
                responseContainer.style.color = "Red";
              } else {
                var responseContainer = document.getElementById(
                  "response-container"
                );
                responseContainer.innerHTML =
                  "An error occurred while verifying OTP.";
                responseContainer.style.color = "Red";
              }
              isRequestInProgress = false;
              
              otpAttempts++; // Increment the OTP verification attempts
              
              // Disable the verify OTP button for one minute
              verifyOtpButton.style.display = "none";
              verifyOtpTimeout = setTimeout(function () {
                verifyOtpButton.style.display = "block";
              }, 60000); // 60,000 milliseconds = 1 minute
            }
          }
        );
      }
    }
  } else {
    alert("You have exceeded the OTP verification attempts limit. Please try again later.");
  }
});
