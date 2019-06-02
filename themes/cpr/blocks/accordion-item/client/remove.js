import PropTypes from 'prop-types';
import './editor.scss';

const { Component } = wp.element;
const { __ } = wp.i18n;
const {
  Button,
  Popover,
  Dashicon,
} = wp.components;

class RemoveButton extends Component {
  constructor() {
    // eslint-disable-next-line prefer-rest-params
    super(...arguments);

    this.state = {
      confirmed: - 1,
    };
  }

  render() {
    const {
      onRemove,
      show,
      style,
      tooltipText = __('Remove block?', 'cpr'),
      tooltipRemoveText = __('Remove', 'cpr'),
      tooltipCancelText = __('Cancel', 'cpr'),
    } = this.props;

    const { confirmed } = this.state;

    if (! show) {
      return '';
    }

    return (
      <Button
        className="cpr-component-remove-button"
        onClick={() => {
          if (- 1 === confirmed) {
            this.setState({
              confirmed: 0,
            });
          }
        }}
        style={style}
      >
        { 0 === confirmed ? (
          <Popover
            className="cpr-component-remove-button-confirm"
            onClose={() => {
              this.setState({
                confirmed: - 1,
              });
            }}
            onClickOutside={() => {
              this.setState({
                confirmed: - 1,
              });
            }}
          >
            { tooltipText }
            <Button
              className="cpr-component-remove-button-confirm-yep"
              onClick={onRemove}
            >
              { tooltipRemoveText }
            </Button>
            <Button
              className="cpr-component-remove-button-confirm-nope"
              onClick={() => {
                this.setState({
                  confirmed: - 1,
                });
              }}
            >
              { tooltipCancelText }
            </Button>
          </Popover>
        ) : '' }
        { <Dashicon icon="trash" /> }
      </Button>
    );
  }
}

RemoveButton.propTypes = {
  show: PropTypes.bool.isRequired,
  onRemove: PropTypes.func.isRequired,
  tooltipText: PropTypes.string.isRequired,
  tooltipRemoveText: PropTypes.string.isRequired,
  tooltipCancelText: PropTypes.string.isRequired,
  style: PropTypes.string.isRequired,
};

export default RemoveButton;
