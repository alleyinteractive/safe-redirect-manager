
/**
 * Edit method for the Deck Block
 * Returns the Deck Component shown in the editor.
 */

import PropTypes from 'prop-types';

const { RichText } = wp.editor;
const { Fragment } = wp.element;
const { __ } = wp.i18n;

const HighlightedEdit = (props) => {
  const {
    attributes: {
      heading,
      content,
    },
    setAttributes,
  } = props;

  return (
    <Fragment>
      <RichText
        value={heading}
        onChange={(nextContent) => {
          setAttributes({
            heading: nextContent,
          });
        }}
        placeholder={__('Header...', 'cpr')}
      />
      <RichText
        value={content}
        onChange={(nextContent) => {
          setAttributes({
            content: nextContent,
          });
        }}
        placeholder={__('Content...', 'cpr')}
      />
    </Fragment>
  );
};

HighlightedEdit.propTypes = {
  attributes: PropTypes.shape({
    header: PropTypes.string.isRequired,
    content: PropTypes.string.isRequired,
  }).isRequired,
  setAttributes: PropTypes.func.isRequired,
};

export default HighlightedEdit;
