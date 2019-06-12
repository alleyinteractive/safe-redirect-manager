import HighlightedItemEdit from './edit';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
  withSelect,
} = wp.data;

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
  edit: withSelect((select, ownProps) => {
    const {
      clientId,
    } = ownProps;
    const { getBlock } = select('core/editor');
    const innerblocksContent = [];

    if (null !== clientId) {
      const parentBlock = getBlock(ownProps.clientId);
      const innerBlocks = parentBlock.innerBlocks.length ?
        parentBlock.innerBlocks :
        [];

      if (null !== innerBlocks.length) {
        innerBlocks.forEach((block) => {
          // Only those with content.
          if (null !== block.attributes.content.length) {
            innerblocksContent.push(block.attributes.content);
          }
        });
      }
    }

    return {
      innerblocksContent,
    };
  })(HighlightedItemEdit),
  save: () => {
    return null;
  },
});
