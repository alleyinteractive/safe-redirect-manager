/**
 * JS for articles (is_single() or is_singular())
 */
// Components
import { ComponentManager } from 'js-component-framework';
import domContentLoaded from 'utils/domContentLoaded';
import headerConfig from 'components/header';
import 'components/footer';

// Import SCSS
import 'scss/index.scss';
import './index.scss';

// Additional utilities specific to this entry point
import articleLogic from './articleLogic'; // eslint-disable-line no-unused-vars

// Create instance of the component manager using `cpr`
const manager = new ComponentManager('cpr');

// Initialize components
domContentLoaded(() => {
  manager.initComponents([headerConfig]); // You can supply an array of configurations to start many components at once
});

if (module.hot) {
  module.hot.accept();
}
