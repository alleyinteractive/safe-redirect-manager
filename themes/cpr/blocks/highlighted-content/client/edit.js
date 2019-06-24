import PropTypes from 'prop-types';
import IconPicker from './iconPicker';
import './edit.scss';

const {
  editor: {
    InnerBlocks,
    RichText,
    InspectorControls,
  },
  i18n: {
    __,
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
    <div className="cpr-component">
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
      <div className="cpr-component-heading-wrapper">
        <IconPicker
          label={__('Icon', 'cpr')}
          value={icon}
          onChange={(value) => {
            setAttributes({ icon: value });
          }}
        />
        <RichText
          className="cpr-component-heading-input"
          placeholder={__('Content Title', 'cpr')}
          value={heading}
          formattingControls={['italic', 'strikethrough', 'link']}
          onChange={(value) => {
            setAttributes({ heading: value });
          }}
        />
      </div>
      <InnerBlocks
        templateLock={false}
      />
    </div>
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
