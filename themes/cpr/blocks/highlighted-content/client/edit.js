import PropTypes from 'prop-types';
import IconPicker from './iconPicker/index';

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
      enableToggle,
    },
    setAttributes,
  } = props;

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
    heading: PropTypes.arrayOf.isRequired,
    enableToggle: PropTypes.bool.isRequired,
    icon: PropTypes.string.isRequired,
  }).isRequired,
  setAttributes: PropTypes.func.isRequired,
};

export default HighlightedItemEdit;
