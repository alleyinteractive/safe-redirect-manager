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
  title: __('CPR Accordion', 'cpr'),
  description: __('Toggle the visibility of content.', 'cpr'),
  icon: 'warning',
  category: 'common',
  keywords: [
    __('accordion'),
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
    collapseOne: {
      type: 'boolean',
      default: false,
    },
  },
  edit: compose([
    withSelect((select, ownProps) => {
      const {
        getBlock,
        isBlockSelected,
        hasSelectedInnerBlock,
      } = select('core/editor');

      const { clientId } = ownProps;

      return {
        block: getBlock(clientId),
        // eslint-disable-next-line max-len
        isSelectedBlockInRoot: isBlockSelected(clientId) || hasSelectedInnerBlock(clientId, true),
      };
    }),
    withDispatch((dispatch, ownProps) => {
      const {
        insertBlock,
      } = dispatch('core/editor');

      const { clientId } = ownProps;

      return {
        insertAccordionItem() {
          insertBlock(createBlock('cpr/accordion-item'), undefined, clientId);
        },
      };
    }),
  ])(AccordionEdit),
  save: AccordionSave,
});
