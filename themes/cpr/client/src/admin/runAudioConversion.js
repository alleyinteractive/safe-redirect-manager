/**
 * Handle media encode in attachment view.
 */
export default function runAudioConversion() {
  document.addEventListener(
    'click',
    (event) => {
      // Bail if we're on the wrong button.
      if (! event.target.matches('button.cpr-encode-audio')) {
        return;
      }

      event.preventDefault();

      const button = event.target;
      const id = button.getAttribute('data-cpr-audio-id');
      const type = button.getAttribute('data-cpr-audio-type');
      const {
        apiFetch,
      } = wp;

      // Disable the encoding buttons while the request is happening.
      Array.from(button.parentElement.querySelectorAll('button'))
        .forEach((encodeButton) => {
          encodeButton.setAttribute('disabled', 'disabled');
        });

      // Send the encoding request to the custom REST endpoint.
      apiFetch({
        data: {
          id,
          type,
        },
        path: '/cpr/v1/audio-transcode-start',
      })
        .catch((error) => {
          console.error(error); // eslint-disable-line no-console
        });
    },
    false
  );
}
