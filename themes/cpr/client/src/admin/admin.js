import './blockEditor';
import './editor';
import loadCustomImageDetailsTemplate from './loadCustomImageDetailsTemplate';
import runAudioConversion from './runAudioConversion';

if (module.hot) {
  module.hot.accept();
}

document.addEventListener('DOMContentLoaded', () => {
  loadCustomImageDetailsTemplate();
  runAudioConversion();
});
