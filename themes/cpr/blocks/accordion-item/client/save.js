import PropTypes from 'prop-types';

const AccordionItemSave = (props) => {
  const {
    heading,
    active,
    itemNumber,
  } = props.attributes;
  const {
    InnerBlocks,
    RichText,
  } = wp.editor;
  const className = active ? 'cpr-accordion-item cpr-accordion-item-active' : 'cpr-accordion-item';

  return (
    <div className={className}>
      <a href={`#accordion-${itemNumber}`} className="cpr-accordion-item-heading">
        <RichText.Content
          className="cpr-accordion-item-label"
          tagName="span"
          value={heading}
        />
        <span className="cpr-accordion-item-collapse">
          <span className="fas fa-angle-right" />
        </span>
      </a>
      <div className="cpr-accordion-item-content">
        <InnerBlocks.Content />
      </div>
    </div>
  );
};

AccordionItemSave.propTypes = {
  attributes: PropTypes.shape({
    itemNumber: PropTypes.string.isRequired,
    active: PropTypes.bool.isRequired,
    heading: PropTypes.array.isRequired,
  }).isRequired,
};

export default AccordionItemSave;
