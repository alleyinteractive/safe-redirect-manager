import HighlightedItemEdit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('cpr/highlighted-content', {
  title: __('Highlighted Content', 'cpr'),
  description: __('A single highlighted content.', 'cpr'),
  category: 'common',
  attributes: {
    heading: {
      type: 'array',
      source: 'children',
      default: __('Content Item', 'cpr'),
    },
    EnableToggle: {
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
