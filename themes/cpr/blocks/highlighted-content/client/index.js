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
    innerblocksContent: {
      type: 'array',
    },
    enableToggle: {
      type: 'boolean',
      default: false,
    },
    icon: {
      type: 'string',
      default: 'fab fa-wordpress-simple',
    },
  },
  edit: HighlightedItemEdit,
  save: () => {
    return null;
  },
});
