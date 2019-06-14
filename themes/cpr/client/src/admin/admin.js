import './blockEditor';
import './editor';
import { loadCustomImageDetailsTemplate } from './loadCustomImageDetailsTemplate';

if (module.hot) {
  module.hot.accept();
}

document.addEventListener('DOMContentLoaded', () => {
  loadCustomImageDetailsTemplate();
});
