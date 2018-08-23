// File for logic corresponding to header component
import { Component } from 'js-component-framework';
import 'images/alleybot-logo.jpg';
import './header.scss';

/**
 * Component for site header
 */
export default class Header extends Component {
  /**
   * Start the component
   */
  constructor(config) {
    super(config);

    // Other Options
    this.offset = this.options.offset;
  }
}
