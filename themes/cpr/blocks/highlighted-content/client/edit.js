import PropTypes from 'prop-types';
import IconPicker from './icons/index';

const {
  editor: {
    InnerBlocks,
    RichText,
    InspectorControls,
  },
  i18n: {
    __,
  },
  element: {
    Fragment,
  },
  components: {
    PanelBody,
    ToggleControl,
  },
} = wp;

const HighlightedItemEdit = (props) => {
  const {
    attributes: {
      heading,
      icon,
      EnableToggle,
    },
    setAttributes,
  } = props;

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody>
          <ToggleControl
            label={__('Toggle content', 'cpr')}
            checked={!! EnableToggle}
            onChange={(value) => {
              setAttributes({ EnableToggle: value });
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
        placeholder={__('Content Title', 'cpr')}
        value={heading}
        onChange={(value) => {
          setAttributes({ heading: value });
        }}
      />
      <InnerBlocks
        templateLock={false}
      />
    </Fragment>
  );
};

HighlightedItemEdit.propTypes = {
  attributes: PropTypes.shape({
    heading: PropTypes.array.isRequired,
    EnableToggle: PropTypes.bool.isRequired,
    icon: PropTypes.string.isRequired,
  }).isRequired,
  setAttributes: PropTypes.func.isRequired,
};

export default HighlightedItemEdit;
