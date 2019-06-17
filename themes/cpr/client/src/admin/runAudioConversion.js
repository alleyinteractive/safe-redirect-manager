import axios from 'axios';

/**
 * Handle media encode in attachement view.
 */
/* eslint-disable */
export function runAudioConversion() {
  document.addEventListener('click', function (event) {
    // Bail if we're on the wrong button.
    if (! event.target.matches('button.cpr-encode-audio')) {
      return;
    }

    event.preventDefault();

    // Make request to custom REST endpoint.
    initiateRestRequst(event);
    // Update contents of <span class="value"> if Processing/Encoded
  }, false);
}

function initiateRestRequst(event) {
  // Get button clicked.
  const button = event.target;

  // Get the type of button clicked. News or Music.
  // const type = button.getAttribute('data-cpr-audio-type');

  // Set api path.
  const path = '';

  // Start processing message after click.
  setProcessMessage('processing');

  // Use axios to ensure fetch and cross-browser compatability.
  axios.get(path)
    .then((response) => {
      // Get headers here.
      return response.data;
    })
    .then((newData) => {
      setProcessMessage('complete');
      // Get data.
      return newData;
    })
    .catch((error) => {
      setProcessMessage('error');
      // Set error message.
      console.error(error)
    }); /* eslint-disable-line no-console */
}

/**
 * Define processing message by type.
 *
 * @param   {string}  type  type of error, processing, error, complete.
 * @return  {string}        message type.
 */
function setProcessMessage(type) {
  const container = document.querySelector('.cpr-notification-message');
  const buttons   = document.querySelectorAll('.cpr-encode-audio');

  // Set value defaults.
  let display  = 'block';
  let disabled = false;
  let innerText = '';

  // Return text based on type.
  switch(type) {
    case 'error':
      disabled = true;
      display = 'block';
      innerText = 'Sorry, there was an error.';
      break;
    case 'processing':
      disabled = true;
      display = 'block';
      innerText = 'Your request is being processed...';
      break;
    case 'complete':
      disabled = true;
      display = 'block';
      innerText = 'Your request is complete.';
      break;
    default:
      disabled = false;
      display = 'none';
      innerText = '';
  }

  // Update buttons and inner text.
  buttons.forEach((elem) => elem.setAttribute('disabled', disabled) );
  container.innerText = innerText;
  container.style.display = display;
}
/* eslint-enable */
