// Internal Dependencies.
import React from 'react';
import PropTypes from 'prop-types';
import RemoveButton from './remove';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const {
  Toolbar,
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
    const {
      block,
    } = this.props;

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
    } = attributes;

    const className = active ? 'cpr-accordion-item cpr-accordion-item-active' : 'cpr-accordion-item';

    return (
      <Fragment>
        <BlockControls>
          <Toolbar controls={[
            {
              icon: 'icon-collapse', // getIcon('icon-collapse'),
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
            <RichText
              tagName="div"
              className="cpr-accordion-item-label"
              placeholder={__('Item label…', 'cpr')}
              value={heading}
              onChange={(value) => {
                setAttributes({ heading: value });
              }}
              formattingControls={['bold', 'italic', 'strikethrough']}
              isSelected={isSelected}
              keepPlaceholderOnFocus
            />
            <button
              className="cpr-accordion-item-collapse"
              // eslint-disable-next-line arrow-body-style
              onClick={() => setAttributes({ active: ! active })}
            >
              <span className="fas fa-angle-right" />
            </button>

            <RemoveButton
              show={isSelectedBlockInRoot}
              tooltipText={__('Remove accordion item?', 'cpr')}
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
    heading: PropTypes.array.isRequired,
  }).isRequired,
  clientId: PropTypes.string.isRequired,
  setAttributes: PropTypes.func.isRequired,
  removeBlock: PropTypes.func.isRequired,
  isSelected: PropTypes.bool.isRequired,
  isSelectedBlockInRoot: PropTypes.bool.isRequired,
  block: PropTypes.func.isRequired,
  rootBlock: PropTypes.func.isRequired,
};

export default AccordionItemEdit;
