import React from 'react';
import PropTypes from 'prop-types';
import IconPicker from './icons/index';

const {
  i18n: {
    __,
  },
  editor: {
    InspectorControls,
    InnerBlocks,
    RichText,
  },
  components: {
    PanelBody,
    ToggleControl,
  },
  data: {
    withSelect,
  },
  element: {
    Fragment,
  },
} = wp;

class HighlightedItemEdit extends React.PureComponent {
  /**
   * Set PropTypes for this component.
   * @type {object}
   */
  static propTypes = {
    attributes: PropTypes.shape({
      heading: PropTypes.string.isRequired,
      enableToggle: PropTypes.bool.isRequired,
      icon: PropTypes.string.isRequired,
    }).isRequired,
    setAttributes: PropTypes.func.isRequired,
    innerblocksContent: PropTypes.string.isRequired,
  };

  /**
   * Component updated.
   */
  componentDidUpdate(prevProps) {
    const {
      innerblocksContent,
      setAttributes,
    } = this.props;

    if (0 !== prevProps.innerblocksContent.length) {
      if (prevProps.innerblocksContent.length !== innerblocksContent.length) {
        console.log(innerblocksContent);
        setAttributes({ innerblocksContent });
      }
    }
  }

  /**
   * Renders this component.
   * @returns {object} - JSX for this component.
   */
  render() {
    const {
      attributes: {
        heading,
        icon,
        enableToggle,
      },
      setAttributes,
    } = this.props;

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody>
            <ToggleControl
              label={__('Toggle content', 'cpr')}
              checked={!! enableToggle}
              onChange={(value) => {
                setAttributes({ enableToggle: value });
              }}
            />
          </PanelBody>
        </InspectorControls>
        <IconPicker
          label={__('Icon', 'cpr')}
          value={icon}
          onChange={(value) => {
            setAttributes({ icon: value });
          }}
        />
        <RichText
          value={heading}
          onChange={(value) => {
            setAttributes({ heading: value });
          }}
        />
        <InnerBlocks
          allowedBlocks={['core/paragraph']}
        />
      </Fragment>
    );
  }
}

const wrapWithSelect = withSelect((select, ownProps) => {
  const { getBlock } = select('core/editor');
  const { clientId } = ownProps;
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

  return { innerblocksContent };
});

export default wrapWithSelect(HighlightedItemEdit);
