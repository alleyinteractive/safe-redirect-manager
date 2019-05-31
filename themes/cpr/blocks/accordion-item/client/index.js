// Internal Dependencies.
import AccordionItemEdit from './edit';
import AccordionItemSave from './save';

const { __ } = wp.i18n;
const {
  compose,
} = wp.compose;
const {
  withSelect,
  withDispatch,
} = wp.data;

const { registerBlockType } = wp.blocks;

registerBlockType('cpr/accordion-item', {
  title: __('Item', 'cpr'),
  parent: ['cpr/accordion'],
  description: __('A single item within a accordion block.', 'cpr'),
  category: 'common',
  supports: {
    html: false,
    className: false,
    anchor: true,
    align: ['wide', 'full'],
    inserter: false,
    reusable: false,
  },
  attributes: {
    heading: {
      type: 'array',
      source: 'children',
      selector: '.cpr-accordion-item-label',
      default: __('Accordion Item', 'cpr'),
    },
    active: {
      type: 'boolean',
      default: false,
    },
    itemNumber: {
      type: 'number',
    },
  },

  edit: compose([
    withSelect((select, ownProps) => {
      const {
        getBlockHierarchyRootClientId,
        getBlock,
        isBlockSelected,
        hasSelectedInnerBlock,
      } = select('core/editor');

      const { clientId } = ownProps;

      return {
        block: getBlock(clientId),
        // eslint-disable-next-line max-len
        isSelectedBlockInRoot: isBlockSelected(clientId) || hasSelectedInnerBlock(clientId, true),
        // eslint-disable-next-line max-len
        rootBlock: clientId ? getBlock(getBlockHierarchyRootClientId(clientId)) : null,
      };
    }),
    withDispatch((dispatch) => {
      const {
        updateBlockAttributes,
        removeBlock,
      } = dispatch('core/editor');

      return {
        updateBlockAttributes,
        removeBlock,
      };
    }),
  ])(AccordionItemEdit),

  save: AccordionItemSave,
});
