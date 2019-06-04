import AccordionEdit from './edit';
import AccordionSave from './save';
import './editor.scss';

const { __ } = wp.i18n;
const {
  createBlock,
} = wp.blocks;
const {
  compose,
} = wp.compose;
const {
  withSelect,
  withDispatch,
} = wp.data;

const { registerBlockType } = wp.blocks;

registerBlockType('cpr/accordion', {
  title: __('Highlighted Content', 'cpr'),
  description: __('Toggle the visibility of a content.', 'cpr'),
  icon: 'list-view',
  category: 'common',
  keywords: [
    __('content'),
    __('list'),
    __('accordion'),
    __('collapsible'),
    __('collapse'),
  ],
  supports: {
    html: false,
    className: false,
    anchor: true,
    align: ['wide', 'full'],
  },
  attributes: {
    itemsCount: {
      type: 'number',
      default: 2,
    },
    DisableToggle: {
      type: 'boolean',
      default: false,
    },
  },
  edit: compose([
    withSelect((select, ownProps) => {
      const { clientId } = ownProps;
      const {
        getBlock,
        isBlockSelected,
        hasSelectedInnerBlock,
      } = select('core/editor');

      return {
        block: getBlock(clientId),
        // eslint-disable-next-line max-len
        isSelectedBlockInRoot: isBlockSelected(clientId) || hasSelectedInnerBlock(clientId, true),
      };
    }),
    withDispatch((dispatch, ownProps) => {
      const { clientId } = ownProps;
      const { insertBlock } = dispatch('core/editor');

      return {
        insertAccordionItem() {
          insertBlock(createBlock('cpr/accordion-item'), undefined, clientId);
        },
      };
    }),
  ])(AccordionEdit),
  save: AccordionSave,
});
