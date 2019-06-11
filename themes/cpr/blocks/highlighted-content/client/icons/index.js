/* eslint-disable consistent-return */
/* eslint-disable react/jsx-closing-tag-location */
/* eslint-disable max-len */
/* eslint-disable react/prefer-stateless-function */
/* eslint-disable react/no-multi-comp */
import classnames from 'classnames/dedupe';
import './icons.scss';

const { Component } = wp.element;
const { __ } = wp.i18n;
const { CPR } = window;
const {
  Dropdown,
  Tooltip,
  BaseControl,
  TextControl,
} = wp.components;

function eachIcons(callback) {
  const { icons } = CPR;

  Object.keys(icons).forEach((key) => {
    callback(icons[key]);
  });
}

const Icon = (props) => {
  const {
    iconData,
    onClick,
    active,
  } = props;

  const style = active ? 'cpr-component-icon-picker-button cpr-component-icon-picker-button-active' : 'cpr-component-icon-picker-button';

  return (
    <IconPicker.Preview
      className={style}
      onClick={onClick}
      data={iconData}
    />
  );
};

export default class IconPicker extends Component {
  render() {
    const {
      value,
      onChange,
      label,
    } = this.props;

    return (
      <IconPicker.Dropdown
        label={label}
        className="cpr-component-icon-picker-wrapper"
        onChange={onChange}
        value={value}
        renderToggle={({ isOpen, onToggle }) => {
          return (
            <Tooltip text={__('Icon Picker', 'cpr')}>
              <IconPicker.Preview
                className="cpr-component-icon-picker-button"
                aria-expanded={isOpen}
                onClick={onToggle}
                name={value}
                alwaysRender
              />
            </Tooltip>
          );
        }
        }
      />
    );
  }
}

class IconPickerDropdown extends Component {
  constructor() {
    // eslint-disable-next-line prefer-rest-params
    super(...arguments);

    this.state = {
      search: '',
    };
  }

  render() {
    const {
      label,
      className,
      onChange,
      value,
      renderToggle,
    } = this.props;

    const dropdown = (
      <Dropdown
        className={className}
        renderToggle={renderToggle}
        renderContent={() => {
          const result = [];

          eachIcons((iconsData) => {
            result.push(<span>{ iconsData.name }</span>);
            result.push(<div className="cpr-component-icon-picker-list">
              { iconsData.icons.map((iconData) => {
                if (
                  ! this.state.search ||
                    (this.state.search && - 1 < iconData.keys.indexOf(this.state.search))
                ) {
                  return (
                    <Icon
                      key={iconData.class}
                      active={iconData.class === value}
                      iconData={iconData}
                      onClick={() => {
                        onChange(iconData.class);
                      }}
                    />
                  );
                }

                return '';
              })
              }
            </div>);
          });

          return (
            <div className="cpr-component-icon-picker">
              <div>
                <TextControl
                  label={__('Icon class', 'cpr')}
                  value={value}
                  onChange={(newClass) => {
                    onChange(newClass);
                  }}
                  placeholder={__('Icon class', 'cpr')}
                />
                <TextControl
                  label={__('Search icon')}
                  value={this.state.search}
                  onChange={(searchVal) => {
                    return this.setState({ search: searchVal });
                  }}
                  placeholder={__('Type to search...', 'cpr')}
                />
              </div>
              <div className="cpr-component-icon-picker-list-wrap">
                { result }
              </div>
            </div>
          );
        }}
      />
    );

    return label ? (
      <BaseControl
        label={label}
        className={className}
      >
        { dropdown }
      </BaseControl>
    ) : (
      dropdown
    );
  }
}

// Dropdown.
IconPicker.Dropdown = IconPickerDropdown;

// Preview icon.
IconPicker.Preview = (props) => {
  const {
    onClick,
    className,
    alwaysRender = false,
  } = props;

  let {
    data,
    name,
  } = props;

  if (! data && name) {
    eachIcons((iconsData) => {
      iconsData.icons.forEach((iconData) => {
        // eslint-disable-next-line max-len
        if (iconData.class && iconData.class === name && iconData.preview) {
          if (iconData.preview) {
            data = iconData;
          } else {
            name = iconData.class;
          }
        }
      });
    });
  }

  let result = '';

  if (data && data.preview) {
    // eslint-disable-next-line react/no-danger
    result = <span dangerouslySetInnerHTML={{ __html: data.preview }} />;
  } else if (name || (data && data.class)) {
    result = <IconPicker.Render name={name || data.class} />;
  }

  return (result || alwaysRender ? (
    <span
      className={classnames(className, 'cpr-component-icon-picker-preview', onClick ? 'cpr-component-icon-picker-preview-clickable' : '')}
      onClick={onClick}
      onKeyPress={() => {}}
      role="button"
      tabIndex={0}
    >
      { result }
    </span>
  ) : '');
};
