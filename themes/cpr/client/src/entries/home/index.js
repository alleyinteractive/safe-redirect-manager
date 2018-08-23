/**
 * Core global JS.
 */
// Instantiate components
// Components
import { ComponentManager } from 'js-component-framework';
import headerConfig from 'components/header';
import 'components/footer';

// Import SCSS
import 'scss/index.scss';

// Additional utilities specific to this entry point
import homeLogic from './homeLogic'; // eslint-disable-line no-unused-vars

// Create instance of the component manager using `cpr`
const manager = new ComponentManager('cpr');

// Initialize components
document.addEventListener('DOMContentLoaded', () => {
  manager.initComponents([headerConfig]); // You can supply an array of configurations to start many components at once
});

if (module.hot) {
  module.hot.accept();
  // This is required for HMR, otherwise you'll need to refresh
  // the page to see JS changes
  manager.initComponents([headerConfig]);
}

