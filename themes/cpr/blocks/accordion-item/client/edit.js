import PropTypes from 'prop-types';
import RemoveButton from './remove/index';
import IconPicker from './icons/index';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const {
  Toolbar,
  Dashicon,
} = wp.components;
const {
  BlockControls,
  InnerBlocks,
  RichText,
} = wp.editor;

class AccordionItemEdit extends Component {
  constructor() {
    // eslint-disable-next-line prefer-rest-params
    super(...arguments);

    this.findParentAccordion = this.findParentAccordion.bind(this);
  }

  findParentAccordion(rootBlock) {
    const { block } = this.props;

    let result = false;

    if (rootBlock.innerBlocks && rootBlock.innerBlocks.length) {
      rootBlock.innerBlocks.forEach((item) => {
        if (! result && item.clientId === block.clientId) {
          result = rootBlock;
        } else if (! result) {
          result = this.findParentAccordion(item);
        }
      });
    }

    return result;
  }

  render() {
    const {
      attributes,
      setAttributes,
      isSelected,
      isSelectedBlockInRoot,
    } = this.props;

    const {
      heading,
      active,
      icon,
    } = attributes;

    let { className = '' } = this.props;

    className = active ? 'cpr-accordion-item cpr-accordion-item-active' : 'cpr-accordion-item';

    return (
      <Fragment>
        <BlockControls>
          <Toolbar controls={[
            {
              icon: 'arrow-down-alt',
              title: __('Collapse', 'cpr'),
              // eslint-disable-next-line arrow-body-style
              onClick: () => setAttributes({ active: ! active }),
              isActive: active,
            },
          ]}
          />
        </BlockControls>
        <div className={className}>
          <div className="cpr-accordion-item-heading">
            <IconPicker
              label={__('Icon', 'cpr')}
              value={icon}
              onChange={(value) => {
                setAttributes({ icon: value });
              }}
            />
            <RichText
              tagName="div"
              className="cpr-accordion-item-label"
              placeholder={__('Item label…', 'cpr')}
              value={heading}
              onChange={(value) => {
                setAttributes({ heading: value });
              }}
              isSelected={isSelected}
              keepPlaceholderOnFocus
            />
            <button
              className="cpr-accordion-item-collapse"
              onClick={() => {
                setAttributes({ active: ! active });
              }}
            >
              <Dashicon icon="arrow-down-alt" />
            </button>

            <RemoveButton
              show={isSelectedBlockInRoot}
              tooltipText={__('Remove content item?', 'cpr')}
              onRemove={() => {
                // eslint-disable-next-line max-len
                const parentAccordion = this.findParentAccordion(this.props.rootBlock);
                if (parentAccordion && parentAccordion.clientId) {
                  this.props.removeBlock(this.props.clientId);

                  if (1 >= parentAccordion.innerBlocks.length) {
                    this.props.removeBlock(parentAccordion.clientId);
                  }
                }
              }}
              style={{
                top: '50%',
                marginTop: - 11,
              }}
            />
          </div>
          <div className="cpr-accordion-item-content">
            <InnerBlocks templateLock={false} />
          </div>
        </div>
      </Fragment>
    );
  }
}

AccordionItemEdit.propTypes = {
  attributes: PropTypes.shape({
    active: PropTypes.bool.isRequired,
    icon: PropTypes.string.isRequired,
    heading: PropTypes.array.isRequired,
  }).isRequired,
  clientId: PropTypes.string.isRequired,
  className: PropTypes.string.isRequired,
  setAttributes: PropTypes.func.isRequired,
  removeBlock: PropTypes.func.isRequired,
  isSelected: PropTypes.bool.isRequired,
  isSelectedBlockInRoot: PropTypes.bool.isRequired,
  block: PropTypes.func.isRequired,
  rootBlock: PropTypes.func.isRequired,
};

export default AccordionItemEdit;
