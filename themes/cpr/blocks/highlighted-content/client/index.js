import HighlightedItemEdit from './edit';

const {
  i18n: {
    __,
  },
  blocks: {
    registerBlockType,
  },
} = wp;

registerBlockType('cpr/highlighted-content', {
  title: __('Highlighted Content', 'cpr'),
  description: __('A single highlighted content.', 'cpr'),
  category: 'common',
  attributes: {
    heading: {
      type: 'string',
      default: __('Content Item', 'cpr'),
    },
    enableToggle: {
      type: 'boolean',
      default: false,
    },
    icon: {
      type: 'object',
      default: {},
    },
  },
  edit: HighlightedItemEdit,
  save: () => {
    const {
      InnerBlocks,
    } = wp.editor;
    return <InnerBlocks.Content />;
  },
});
