const { __ } = wp.i18n;
const {
  Component,
  Fragment,
} = wp.element;
const {
  PanelBody,
  ToggleControl,
  IconButton,
} = wp.components;
const {
  InspectorControls,
  InnerBlocks,
} = wp.editor;

/**
 * Returns the layouts configuration for a given number of items.
 *
 * @param {number} attributes items attributes.
 *
 * @return {Object[]} Tabs layout configuration.
 */
const getTabsTemplate = (attributes) => {
  const {
    itemsCount,
  } = attributes;
  const result = [];

  // eslint-disable-next-line no-plusplus
  for (let k = 1; k <= itemsCount; k ++) {
    result.push(['cpr/accordion-item', { itemNumber: k }]);
  }

  return result;
};

class AccordionEdit extends Component {
  constructor() {
    // eslint-disable-next-line prefer-rest-params
    super(...arguments);

    this.maybeUpdateItemsCount = this.maybeUpdateItemsCount.bind(this);
  }

  componentDidMount() {
    this.maybeUpdateItemsCount();
  }

  componentDidUpdate() {
    this.maybeUpdateItemsCount();
  }

  /**
   * Update current items number.
   */
  maybeUpdateItemsCount() {
    const { itemsCount } = this.props.attributes;
    const {
      block,
      setAttributes,
    } = this.props;

    if (itemsCount !== block.innerBlocks.length) {
      setAttributes({
        itemsCount: block.innerBlocks.length,
      });
    }
  }

  render() {
    const {
      attributes,
      setAttributes,
      isSelectedBlockInRoot,
      insertAccordionItem,
    } = this.props;
    const { collapseOne } = attributes;

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <ToggleControl
              label={__('Collapse one item only', 'cpr')}
              checked={!! collapseOne}
              // eslint-disable-next-line arrow-body-style
              onChange={(val) => setAttributes({ collapseOne: val })}
            />
          </PanelBody>
        </InspectorControls>
        <div className="cpr-accordion">
          <InnerBlocks
            template={getTabsTemplate(attributes)}
            allowedBlocks={['cpr/accordion-item']}
          />
        </div>
        { isSelectedBlockInRoot ? (
          <div className="cpr-accordion-add-item">
            <IconButton
              icon="insert"
              onClick={() => {
                insertAccordionItem();
              }}
            >
              { __('Add More Item', 'cpr') }
            </IconButton>
          </div>
        ) : '' }
      </Fragment>
    );
  }
}

export default AccordionEdit;
