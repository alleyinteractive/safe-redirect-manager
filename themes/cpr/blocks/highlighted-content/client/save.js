import PropTypes from 'prop-types';

const { RichText } = wp.editor;

const HighlightedSave = (props) => {
  const {
    className,
  } = props;

  return (
    <div className={className} >
      <RichText.content />
    </div>
  );
};

HighlightedSave.propTypes = {
  className: PropTypes.string.isRequired,
};

export default HighlightedSave;
